<?php 
/**
* parse info from uploaded PDF into array
* extract text from pdf
* extract avn, wko, desc, serials, quantity, model, colour, grille, connectors, fixings, waveguide
* transport, wheel, part num, status from text
*/
use Smalot\PdfParser\parser;
use App\DTOs\PdfData;
use App\DTOs\ExtractedFields;

    class PDFWorkorderParserService {

        public function __construct(){}

        public CONST MULTIPLE_VERSION_MODELS = [
            'F5','F55', 'F115', 'F215', 'F118', 'F218', 'F121', 'EVO 2'
        ];

        public CONST MAY_HAVE_PX = [
            'RES 1', 'EVO 6EH', 'EVO 6SH', 'EVO 7EH', 'EVO 7SH'
        ];

        public function validatePDFFileUpload(array $file) {

            if($file['error' !== UPLOAD_ERR_OK]) {
                return PdfData::failure('Error uploading file');
            }

            if($file['size'] > 10_000_000) {
                return PdfData::failure('File size too large');
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            if(!$mime === 'application/pdf') {
                return PdfData::failure('Invalid file format');
            }

        }

        public function parse($pdf): PdfData {

            //validate PDF upload
            $pdfData = $this->validatePDFFileUpload($pdf);
            if($pdfData->success === false) throwErr(51, 'Could not parse PDF: '.$pdfData->error, $pdfData);

            //Parse pdf
            try {
                $parser = new Parser();
                $parsedPdf = $parser->parseFile($_FILES['pdf']['tmp_name']);
            } catch (Exception $e) {
                return PdfData::failure('Pdf parsing failed: '.$e->getMessage());
            }

            //extract and normalise text
            $text = $parsedPdf->getText();
            $nText = $this->normaliseText($text);

            //run regexes
            $extracted = $this->extractData($nText);

            return PdfData::success($extracted,$text, $nText);
        }
        

        private function normaliseText(string $text):string {
            $text = preg_replace('/\s+/', ' ', $text);           // collapse whitespace
            $text = preg_replace('/[^\x20-\x7E]/', '', $text);   // strip non-ASCII (adjust if you need Unicode)
            $text = trim($text);

            return $text;
        }

        private function extractData(string $text): ExtractedFields{
            $extracted = new ExtractedFields (
                avn: ltrim($this->extractionHelper('/AVN\/(\d{5})/i',$text),"0"),
                wko: $this->extractionHelper('/WKO\/(\d{5}\/[A-Z]-\d{2})/i',$text),
                cab_model: $this->extractModel($text),
                cab_finish: $this->extractCabColour($text),
                // cab_finish_ral: $this->extractCabRal($text),
                waveguide_finish: $this->extractionHelper('/s, (.*?)\swaveguide/i',$text),
                grille_finish: $this->extractGrilleColour($text),
                // grille_finish_ral: $this->extractGrilleRal($text),
                quantity: $this->extractionHelper('/Required:\s(\d*)/i',$text),
                serials: $this->extractSerials($text),
                connectors: $this->extractConnector($text),
                fixings: $this->extractFixings($text),
                wheels: ($this->extractionHelper('/WK-4IN\sto\sbe\sfitted/i',$text)) ? true : false,
                transport: $this->extractionHelper('/(Deliver\sto\sF1)/i',$text) ?? $this->extractionHelper('/(To\sstorage)/i',$text),
                notes: $this->extractionHelper('/Additional Instructions\s(.*)\sRegistered\sAddress/i',$text)
            
            );
            return $extracted;
        }

        private function extractModel(string $text): string {
            //needs help detecting SH models i.e RES 2SH, EVO 2SH...
            $model = $this->extractionHelper('/Description:([A-z]{1,4}[0-9]{1,3}.?\d{1,2}?)/',$text);
            if(!$model) $model = $this->formatModel($this->extractionHelper('/Description:([A-z]{1,4}[0-9]{1,3}\w{2})/i', $text));
            //check if it's a model that has multiple versions 
            $versionCheck = in_array((string) $model, PDFWorkorderParserService::MULTIPLE_VERSION_MODELS, TRUE);
            if($versionCheck) { //check for MkI or MkII
                $model = $this->extractionHelper('/Description:([A-z]{1,4}[0-9]{1,3}.?\d{1,2}?\s?\w{3,4})/i',$text);
                // dumpAndDie($model);
                $model = $this->formatModel($model); 
            }
            return $model;
        }

        private function formatModel(string $model): string { //turn EVO2 into EVO 2 and *mkii into * MkII
            if($model[4] === 's' || $model[4] === 'S') {
                $model = strtoupper($model);
                $model = preg_replace('/(\w{3})(\dSH)/', '$1 $2', $model);
            }
            if($model[0] === 'E') {
                $model = strtoupper($model);
                $model = preg_replace('/\b(EVO|RES)(\d+)\b/', '$1 $2', $model);
            }
            if($model[0] === 'F') {
                $model = preg_replace('/(F\d{3})mk(\s?.)/i', '$1 Mk$2',$model);
            }
             
            
            return $model;
        }

        private function extractionHelper(string $pattern, string $text): ?string {
            preg_match($pattern, $text, $matches);
            return isset($matches[1]) ? trim($matches[1]) : null;
        }

        private function extractSerials(string $text): ?string {

            $results = [];

            //capture serial line(s) with serial numbers
            $labelPattern = '/Serial\s*(?:Numbers?|Nos?\.?)\s*:(.+?)(?:\n|$)/i';
            if (preg_match($labelPattern, $text, $labelMatch)) { 
                $lines = [$labelMatch[1]];
            } else {
                $lines = array_filter(
                    explode('/\R/', $text),
                    fn($line) => preg_match('/(?:[A-Z][A-Z0-9]*\/)?\d{4,6}\s*(?:-|–|\bto\b|&|$)/', $line)
                );
            }

            if (empty($lines)) {
                return null;
            }

            //parse segments from each line
            $rangeRegex  = '/(?:[A-Z][A-Z0-9]*\/)?(\d{4,6})\s*(?:-|–|\bto\b|&)\s*(?:[A-Z][A-Z0-9]*\/)?(\d{4,6})/i';
            $singleRegex = '/(?:[A-Z][A-Z0-9]*\/)?(\d{4,6})/i';

            // Strips DD/MM/YYYY, DD-MM-YYYY, DD.MM.YYYY and bare 4-digit years
            $datePattern = '/\b\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4}\b|\b(19|20)\d{2}\b/';
            $ralPattern = '/RAL\s\d{3,5}/';

            foreach ($lines as $line) {
                $line     = trim($line);
                $line     = preg_replace($datePattern, '', $line); // ← strip dates before parsing
                $line     = preg_replace($ralPattern, '', $line); // ← strip ral before parsing
                $segments = preg_split('/\s*\+\s*/', $line);
                $lineResults = [];
                
                foreach ($segments as $segment) {
                    $segment = trim($segment);
                    
                    
                    //pad numbers so they're always 5 digits long
                    if (preg_match($rangeRegex, $segment, $m)) { 
                        $start         = str_pad($m[1], 5, '0', STR_PAD_LEFT);
                        $end           = str_pad($m[2], 5, '0', STR_PAD_LEFT);
                        $lineResults[] = "{$start} - {$end}";

                    } elseif (preg_match($singleRegex, $segment, $m)) {
                        $lineResults[] = str_pad($m[1], 5, '0', STR_PAD_LEFT);
                    }
                }
                //join multiple ranges if they exist
                if (!empty($lineResults)) {
                    $results[] = implode('/', $lineResults);
                }
            }

            $unique = array_values(array_unique($results));
            return $unique[0] ?? null;
        }

        private function extractCabColour(string $text): ?string {

            //pull colour from text
            $colour = $this->extractionHelper('/s, (.*?)\scabinet/',$text);
            if(strtolower($colour) === 'custom') {
                $colour = $this->extractionHelper('/Cabinet\scolour:\sRAL\s\d{2,5}\s-\s(\w+\s\w+)/i',$text);
            }
            return $colour;
        }

        private function extractGrilleColour(string $text): ?string {
            //need to account for 'throat grille' variations
            //need to remove 'S/Steel' or 'm/Steel' from final colour
            $colour = ucwords($this->extractionHelper('/t, (.*?)\sgrille/',$text)," \t\r\n\f\v'");
            if($colour[1] === "'" | $grille[1] === '/') {
                //find other reference
                $colour = $this->extractionHelper('/\sGrill\w{0,1}\scolour:\sRAL\s\d{2,5}\s-\s(\w+\s\w+)/i',$text);
            }
            return $colour;
        }

        private function extractConnector(string $text): ?string {
            $connector = $this->extractionHelper('/(\w{3}\s&\s\w{2}\d)\sconnectors/',$text);
            if (!$connector) $connector = $this->extractionHelper('/(\w{2}\d)\sconnectors/',$text);
            if (!$connector) $connector = $this->extractionHelper('/(NL4\s&\sPhoenix)\sconnectors/',$text);
            return $connector;
        }

        private function extractCabRal(string $text): ?string {
            $ral = $this->extractionHelper('/s, (RAL\s\d{2,5})/',$text);
            return $ral;
        }

        private function extractFixings(string $text): string {
            $fixings = $this->extractionHelper('/\s(\w{1}.{1}steel)\sfixings/i',$text);
            // dumpAndDie($text, $fixings);
            if (!$fixings) { 
                $fixings = $this->extractionHelper('/\s(black\s\w{1}.{1}steel)\sfixings/i', $text); //fallback for black plated steel fixings
            }
            if (!$fixings) {
                $fixings = $this->extractionHelper('/(SS fixings)/i', $text);   
                if($fixings) $fixings = 'S/Steel';
            }
            return $fixings;
        }
    };




?>