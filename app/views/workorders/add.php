<?php require APPROOT . '/views/inc/header.php'; ?>


<a class="" href="javascript:history.go(-1)"><?php include APPROOT.'/views/components/icons/backicon.php'; ?>Back</a>

<?php 

require '../vendor/autoload.php';
use Smalot\PdfParser\Parser;

$pdfdata = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset(($_FILES['pdf']))) {
    if($_FILES['pdf']['error'] === UPLOAD_ERR_OK) {

        function extractData($pattern, $text) {
            preg_match($pattern, $text, $matches);
            return isset($matches[1]) ? trim($matches[1]) : null;
        }
        $parser = new Parser();
        $pdf = $parser->parseFile($_FILES['pdf']['tmp_name']);
        $text = $pdf->getText();
        $avn = extractData('/AVN_(\d{5})/', $_FILES['pdf']['name']);
        $targetFile = TEMPDIR.basename('AVN_'.$avn.'.pdf');
        $upload_file = move_uploaded_file($_FILES['pdf']['tmp_name'], $targetFile);
        if($upload_file) {
        } else {
            throwErr(107,'Pdf upload error!');
        };
        unset($_FILES['pdf']); 

       

        function cleanSerials($serial) {
            $serialString = $serial;
            preg_match('/([A-z])/',$serialString, $hasChar);
            if ($hasChar) {
                preg_match('/\/(\d{5}).*?\/(\d{5})/', $serialString, $matches);
                $serialString = ltrim($matches[1],'0').' - '.ltrim($matches[2],'0');
                ($serialString === ' - ') ? $serialString = '' : '' ;
            } else {
                preg_match('/(\d{5})\s-\s(\d{5})/', $serialString, $matches);
                $serialString = ltrim($matches[1],'0').' - '.ltrim($matches[2],'0');
                ($serialString === ' - ') ? $serialString = '' : '' ; 
            }
            $serialString === '' ? $serialString = $serial : '';
            //remove any model name from start if present
            preg_match('/(.*?)\/\d.+ -/',$serialString, $hasPrefix);
            // print_r($hasPrefix);
            if(isset($hasPrefix[1])) {
                $serialString = substr($serialString, strlen($hasPrefix[1])+1);
            }
            return $serialString;
        }

    
        $pdfdata = [
            'AVN' => ltrim(extractData('/AVN\/(\d{5})/', $text),'0'),
            'WKO' => ltrim(extractData('/WKO\/(\d{5}\/[A-Z]-\d{2})/', $text),'0'),
            'desc' => trim(preg_replace('/\s+/', ' ', extractData('/Description:\s*(.*?)\s*Serial Numbers:/s', $text))),
            'serials' => extractData('/Serial Nos:\s*(.*?)\nCharge and Quantity/s', $text) ?? extractData('/Serial Numbers:(.*?)\sCharge/', $text),
            'quantityRequired' => extractData('/Qty Required:\s*(\d+)/', $text),
            'model' => extractData('/Description:(.*?)\s/',$text) ?? extractData('/(.*?)\s/',$text),
            'colour' => ucwords(extractData('/s,(.*?)\scabinet/', $text) ?? extractData('/s,(.*?)\swaveguide/', $text)," "),
            'grille' => ucwords(extractData('/t,(.*?)\sgrille/',$text)," \t\r\n\f\v'"),
            'connectors' => extractData('/d,(.*?)\sconnectors/',$text) ?? extractData('/\),(.*?)\sconnectors/',$text),
            'fixings' => ucwords(extractData('/e,\s(.*?)\sfixings,/',$text),"'"),
            'waveguide' => extractData('/s,\s(.*?)\swaveguide,/',$text),
            'transport' => (extractData('/(Deliver\sto\sF1)/',$text) ?? extractData('/(To\sstorage)/',$text)) ?? 'TBC',
            'wheels' => (extractData('/WK-4IN\sto\sbe\sfitted/',$text)) ? true : false,
            'part_no' => extractData('/(F1-\d{3}-\d{3})/', $text),
            'status' => 'Upcoming'// -- eventually this should set to either 'to be built' or 'waiting for parts' depending on stock levels
        ]; 

        // $pdfdata['colour'] = ucwords(extractData('/s,(.*?)\scabinet/', $text));
        // echo $pdfdata['colour'];
        // die('oh bother');

        empty($pdfdata['serials']) ? $pdfdata['serials'] = extractData('/(\d{5}\s-\s\d{5})/', $text): '';
        
        $pdfdata['serials'] = cleanSerials($pdfdata['serials']);

        $modelCheck = extractData('/(mkII)/',$text);
        if($modelCheck) {
            $pdfdata['model'] = substr($pdfdata['model'],0,strlen($pdfdata['model'])-4).' MkII';
        }
        $modelCheck = strtoupper(substr($pdfdata['model'],0,3));
        if($modelCheck === 'EVO' | $modelCheck === 'RES') {
            $pdfdata['model'] = strtoupper($pdfdata['model']);
            $pdfdata['model'] = substr($pdfdata['model'],0,3).' '.substr($pdfdata['model'],3);
            unset($modelCheck);
            $modelCheck = extractData('/(SH)/',$pdfdata['model']);
            if($modelCheck) {
                $pdfdata['waveguide'] = $pdfdata['colour'];
                unset($pdfdata['colour']);
            }
        }
        if($pdfdata['grille']==='No') {
            $pdfdata['grille'] = '';
        }
        preg_match('/(Throat)/',$pdfdata['grille'],$matches);
        if(isset($matches[0])) {
            $grilleLen = strlen($pdfdata['grille']);
            $pdfdata['grille'] = substr($pdfdata['grille'],0,$grilleLen-7);
            if($pdfdata['grille'] === "Brushed S'Steel") {$pdfdata['grille'] = "Brushed S/Steel";
            }
            if($pdfdata['grille'] === "Black S'Steel") {$pdfdata['grille'] = "Black S/Steel";
            }
        }  

    }
}

// $data->data->part_no = $pdfdata['part_no'];

?>

<div class="">
    <form action="" method="post" enctype="multipart/form-data">
        <input style="width:calc(100%/1)" type="file" name="pdf" accept="application/pdf" required>
        <button type="submit">Upload Advice Note</button>
    </form>
</div>

<!-- <?php if ($pdfdata): ?>
    <h3>Advice Note</h3>
    <table>
        <?php foreach ($pdfdata as $key => $value): ?>
            <tr>
                <th><?php echo htmlspecialchars($key) ?></th>
                <td><?php echo htmlspecialchars($value); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
 
?>
<?php endif; ?> -->

<div class="row fade-in">
    <div class="card card-body bg-light mt-2 text-center">
        <h2>Add Work Order</h2>
        <p>Add a WKO from the database using the form below or uplaod an AVN using the form above</p>
        <br>
        <form action="<?php echo URLROOT; ?>workorders/add/" method="post">
                <div class="pt-5 row">
                <label for="wko">WKO: <sup>*</label>
                    <input 
                        type="text" 
                        name="wko" 
                        class= "<?php echo (!empty($data->errors->err_wko))? 'is-invalid' : '';?>" 
                        value="<?php echo $pdfdata['WKO'] ?? $data->data->wko ;?>">
                    <span class="invalid-feedback"><?php echo $data->errors->err_wko;?></span>
                </div>
                <div class="pt-3">
                    <label for="avn">AVN: <sup>*</label>
                    <input 
                        name="avn" 
                        class= "<?php echo (!empty($data->errors->err_avn))? 'is-invalid' : '';?>" 
                        value="<?php echo $pdfdata['AVN'] ?? $data->data->avn;?>">
                    </input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_avn;?></span>
                </div>
                <div class="pt-3">
                    <label for="cab_model_id">Speaker Model: <sup>*</label>
                    <select 
                        name="cab_model_id" 
                        class= "<?php echo (!empty($data->errors->err_cab_model))? 'is-invalid' : '';?>" 
                        value="<?php echo  $data->data->model_id ?? $pdfdata['model'] ;?>">
                        <option value="<?php if(isset($data->data->model_id)) {echo $data->data->model_id;} else {if(isset($pdfdata['model'])){echo $pdfdata['model'];} else {echo '';};}?>"><?php if(isset($data->data->model_id)) {echo $data->data->model_id;} else {if(isset($pdfdata['model'])){echo $pdfdata['model'];} else {echo '- -';};} ?></option>
                        <?php foreach($data->models as $model) : ?>
                            <option value="<?php echo $model->name;?>"><?php echo $model->name;?></option>
                         <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data->errors->err_cab_model;?></span>
                </div>
                <div class="pt-3">
                    <label for="cab_finish_id">Speaker Colour: <sup>*</label>
                    <select 
                        name="cab_finish_id" 
                        class= "<?php echo (!empty($data->errors->err_cab_colour))? 'is-invalid' : '';?> cabColourSel" 
                        value="<?php echo $data->data->cab_finish_id;?>">
                        <option value="<?php if(isset($data->data->finish_id)) {echo $data->data->finish_id;} else {if(isset($pdfdata['colour'])){echo $pdfdata['colour'];} else {echo '';};}?>"><?php if(isset($data->data->finish_id)) {echo $data->data->cab_finish->name;} else {if(isset($pdfdata['colour'])){echo $pdfdata['colour'];} else {echo '- -';};}?></option>
                        <?php foreach($data->finishes as $finish) : ?>
                            <?php if($finish->type != 'Metal' & $finish->type != 'Polyurethane') :?>
                                <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                            <?php endif; ?>
                         <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data->errors->err_cab_colour;?></span>
                </div>
                <div class="pt-3">
                    <label for="grille_finish_id">Speaker Grille: </label>
                    <select 
                        name="grille_finish_id" 
                        class= "<?php echo (!empty($data->errors->err_grille_colour))? 'is-invalid' : '';?> " 
                        value="<?php echo $data->data->grille_finish_id;?>">
                        <option value="<?php if(isset($data->data->grille_finish_id)) {echo $data->data->grille_finish_id;} else {if(isset($pdfdata['grille'])){echo $pdfdata['grille'];} else {echo '';};}?>"><?php if(isset($data->data->grille_finish_id)) {echo $data->data->grille_finish->name;} else {if(isset($pdfdata['grille'])){echo $pdfdata['grille'];} else {echo '- -';};}?></option>
                        <?php foreach($data->finishes as $finish) : ?>
                            <?php if($finish->type != 'Polyurethane' & $finish->type != 'Wood' & $finish->type != 'Weather Resistant') :?>
                                <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data->errors->err_grille_colour;?></span>
                </div>
                <div class="pt-3 waveguideSel <?php echo(empty($data->data->waveguide->name) ? '' :'block'); ?>">
                    <label for="waveguide_finish_id">Waveguide Colour: </label>
                    <select 
                        name="waveguide_finish_id" 
                        class= <?php echo (!empty($data->errors->err_waveguide_colour))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->waveguide_finish_id ;?>">
                        <option value="<?php echo $data->data->waveguide ?? $pdfdata['waveguide'] ?>"><?php echo ($data->data->waveguide ?? $pdfdata['waveguide']) ?? '- -' ?></option>
                        <?php foreach($data->finishes as $finish) : ?>
                            <?php if($finish->type === 'Polyurethane') :?>
                                <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                            <?php endif; ?>
                         <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data->errors->err_waveguide_colour;?></span>
                </div>
                <div class="pt-3">
                    <label for="wheels">Wheels:</label>
                    <input name="wheels" 
                                type="checkbox"
                        class= "" 
                        value="<?php echo $data->data->wheels ?? $pdfdata['wheels'];?>"></input>
                </div>
                <div class="pt-3">
                    <label for="quantity_required">Quantity Required: <sup>*</label>
                    <input name="quantity_required" 
                        class= "<?php echo (!empty($data->errors->err_quantity_required))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->quantity_required ?? $pdfdata['quantityRequired'];?>"></input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_quantity_required;?></span>
                </div>
                <div class="pt-3">
                    <label for="serials">Serials: <sup>*</label>
                    <input name="serials" 
                        class= "<?php echo (!empty($data->errors->err_serials))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->serials ?? $pdfdata['serials'];?>"></input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_serials;?></span>
                </div>
                <div class="pt-3">
                    <label for="wko_status">Status: </label>
                    <select name="wko_status" 
                        class= "<?php echo (!empty($data->errors->err_wko_status))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->wko_status;?>">
                        <option value=""><?php if(isset($data->data->wko_status)) {echo $data->data->wko_status;} else { echo ' - -';} ?></option>
                        <option value="In Progress">In Progress</option>
                        <option value="On Hold">On Hold</option>
                        <option value="To Be Built">To Be Built</option>
                        <option value="Upcoming">Upcoming</option>
                        <option value="Completed">Completed</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $data->errors->err_wko_status;?></span>
                </div>
                <div class="pt-3">
                    <label for="wko_delivery">Delivery: </label>
                    <input name="wko_delivery" 
                        class= "<?php echo (!empty($data->errors->err_wko_delivery))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->wko_delivery ?? $pdfdata['transport'];?>"></input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_wko_delivery;?></span>
                </div>
                <div class="pt-3">
                    <label for="wko_notes">Notes: </label>
                    <textarea name="wko_notes" 
                        class= "<?php echo (!empty($data->errors->err_wko_notes))? 'is-invalid' : '';?>" 
                        value="<?php echo($data->data->wko_notes); ?>"><?php echo($data->data->wko_notes); ?></textarea>
                    <span class="invalid-feedback"><?php echo $data->errors->err_wko_notes;?></span>
                </div>
                <!-- <input class="hidden" type="text" name="part_no" value="<?php echo $pdfdata['part_no']; ?>"></input> -->
                <input type="submit" 
                    class="btn" 
                    value="Submit">
            </form>
    </div>
</div>


<?php echo '<PRE>errs:'; print_r($data->errors);?>
<?php echo 'pdfData:'; print_r($pdfdata);?>

<?php require APPROOT . '/views/inc/footer.php'; ?>