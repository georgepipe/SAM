<?php

    namespace App\DTOs;

    class PdfData{

        public function __construct(
            //define variables
            public readonly bool $success,
            public readonly ?string $error,
            public readonly ExtractedFields $extracted,
            public readonly string $rawText,
        ){}

        public static function failure(string $error):self {
            return new self(false, $error, [],'');
        }

        public static function success(ExtractedFields $extracted, string $rawText) {
            return new self(
                success : true,
                error: null,
                extracted: $extracted,
                rawText: $rawText
            );
        }
    }

    class ExtractedFields {
        
        function __construct(
            public readonly ?string $avn,
            public readonly ?string $wko,
            public readonly string $cab_model,
            public readonly ?string $cab_finish,
            // public readonly ?string $cab_finish_ral,
            public readonly ?string $waveguide_finish,
            public readonly ?string $grille_finish,
            // public readonly ?string $grille_finish_ral,
            public readonly int $quantity,
            public readonly ?string $serials,
            public readonly ?string $connectors,
            public readonly ?string $fixings,
            public readonly ?bool $wheels,
            public readonly ?string $transport,
            public readonly ?string $notes
            

            ){}
    }
    
    
    ?>
    