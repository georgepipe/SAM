<?php require APPROOT . '/views/inc/header.php'; ?>

<?php if(isset($_SESSION['post_message'])) {echo flash('post_message');} ?>
<div class="">
    <a class="" href="javascript:history.go(-1)"><?php include APPROOT.'/views/components/icons/backicon.php'; ?>Back</a>
</div>

<section>
    <div class="fade-in addform">
        <div class="grid justify-center text-center mb-8">
            <h1 >Add Work Order</h1>
            <p>Add/Upload a AVN to the database using the form or button below</p>
            <br>
            <div class="border-4" style="display: flex; justify-content: center;">
                <form id="addForm" action="" onsubmit="" method="post" enctype="multipart/form-data">
                    <input style="padding-left:250px; color:transparent;" type="file" name="pdf" title=" " accept="application/pdf" required onchange="this.form.submit();">
                </form>
            </div>
            <form class="input-form" action="<?php echo URLROOT; ?>workorders/add/" method="post">
                    <div class="form-group">
                    <label for="wko">WKO: <sup>*</label>
                        <input 
                            type="text" 
                            name="wko" 
                            id="wko"
                            class= "<?php echo (!empty($data->errors->err_wko))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->pdfData->wko ?? ($data->data->wko ?? '') ;?>">
                        <span class="invalid-feedback"><?php echo $data->errors->err_wko ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="avn">AVN: <sup>*</label>
                        <input 
                            name="avn" 
                            id="avn"
                            class= "<?php echo (!empty($data->errors->err_avn))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->pdfData->avn ?? ($data->data->avn ?? '');?>">
                        </input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_avn ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="cab_model_id">Speaker Model: <sup>*</label>
                        <select 
                            name="cab_model_id" 
                            id="cab_model_id"
                            class= "cabSel <?php echo (!empty($data->errors->err_cab_model_id))? 'is-invalid' : '';?>" 
                            value="">
                            <option 
                                value="
                                    <?php if(isset($data->data->cab_model_id)) {echo $data->data->cab_model_id;} 
                                        else 
                                            {if(isset($data->pdfData->cab_model)){echo $data->pdfData->cab_model;} 
                                                else {;};}
                                    ?>">
                                    <?php if(isset($data->data->cab_model_id) && !empty($data->data->cab_model_id)) {echo $data->models[$data->data->cab_model_id-1]->name;} 
                                        else 
                                            {if(isset($data->pdfData->cab_model)){echo $data->pdfData->cab_model;} 
                                                else {echo '- -';};} ?>
                            </option>

                            <?php foreach($data->models as $model) : ?>
                                <option value="<?php echo $model->mid;?>"><?php echo $model->name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_cab_model_id ?? '';?></span>
                    </div>
                    <div class="ccDiv <?php if($pdf){if(!empty($data->pdfData->colour) | !empty($data->data->cab_finish_id)){echo 'form-group';} else {echo 'hidden';}} else {echo 'form-group';} ?>">
                        <label for="cab_finish_id">Speaker Colour: <sup>*</label>
                        <select
                            name="cab_finish_id" 
                            id="cab_finish_id"
                            class= "<?php echo (!empty($data->errors->err_cab_finish_id))? 'is-invalid' : '';?> cabColourSel" 
                            value="<?php echo $data->data->cab_finish_id ?? '';?>">
                            <option 
                                value="
                                    <?php if(isset($data->data->cab_finish_id)) {echo $data->data->cab_finish_id;} 
                                        else 
                                            {if(isset($data->pdfData->cab_finish)){echo $data->pdfData->cab_finish;} 
                                                else 
                                                    {echo '';};}?>">
                                    <?php if(isset($data->data->cab_finish_id) && !empty($data->data->cab_finish_id)) {echo $data->finishes[$data->data->cab_finish_id-1]->name;} 
                                        else 
                                            {if(isset($data->pdfData->cab_finish)){echo $data->pdfData->cab_finish;} 
                                                else 
                                                    {echo '- -';};}?>
                            </option>
                            <?php foreach($data->finishes as $finish) : ?>
                                <?php if($finish->type != 'Metal' & $finish->type != 'Polyurethane') :?>
                                    <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_cab_finish_id ?? '';?></span>
                    </div>
                    <div class="gDiv <?php if(!empty($data->pdfData->grille_finish) || !empty($data->data->grille_finish_id) || !empty($data->errors->err_grille_colour)){echo 'form-group';} else {echo 'hidden';} ?>">
                        <label for="grille_finish_id">Speaker Grille: </label>
                        <select 
                            name="grille_finish_id" 
                            id="grille_finish_id" 
                            class= "grilleSel <?php echo (!empty($data->errors->err_grille_colour))? 'is-invalid' : '';?> " 
                            value="<?php echo $data->data->grille_finish_id ?? '';?>">
                            <option value="<?php 
                                if(isset($data->data->grille_finish_id)) 
                                    {echo $data->data->grille_finish_id;} 
                                else 
                                    {if(isset($data->pdfData->grille_finish))
                                        {echo $data->pdfData->grille_finish;} 
                                            else {echo '';};}?>">
                                <?php if(!empty($data->data->grille_finish_id)) 
                                    {echo $data->finishes[$data->data->grille_finish_id-1]->name;} 
                                        else {if(isset($data->pdfData->grille_finish))
                                            {echo $data->pdfData->grille_finish;} else {echo '- -';};}?>
                            </option>

                            <?php foreach($data->finishes as $finish) : ?>
                                <?php if($finish->type != 'Polyurethane' & $finish->type != 'Wood' & $finish->type != 'Weather Resistant') :?>
                                    <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_grille_colour ?? '';?></span>
                    </div>
                    <div class="wDiv <?php if(!empty($data->pdfData->waveguide_finish) || !empty($data->data->waveguide_finish_id) || !empty($data->errors->err_waveguide_finish_id)) {echo 'form-group';} else {echo 'hidden';} ?> ">
                        <label for="waveguide_finish_id">Waveguide Colour: </label>
                        <select 
                            name="waveguide_finish_id" 
                            id="waveguide_finish_id" 
                            class="waveguideSel <?php echo (!empty($data->errors->err_waveguide_finish_id))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->waveguide_finish_id ?? '';?>">
                            <option value="<?php echo $data->data->waveguide_finish_id ?? ($data->pdfData->waveguide_finish ?? '') ?>"><?php echo ($data->data->waveguide_finish_id ?? ($data->pdfData->waveguide_finish ?? '')) ?? '- -' ?></option>
                            <?php foreach($data->finishes as $finish) : ?>
                                <?php if($finish->type === 'Polyurethane') :?>
                                    <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_waveguide_finish_id ?? '';?></span>
                    </div>
                    <div class="whDiv <?php if(!empty($data->pdfData->wheels) | !empty($data->data->wheels)){echo 'form-group';} else {echo 'hidden';} ?>">
                        <label for="wheels">Wheels:</label>
                        <input name="wheels" 
                                type="checkbox"
                                id="wheels"
                                class= "" 
                            value="<?php echo $data->data->wheels ?? ($data->pdfData->wheels ?? '');?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity: <sup>*</label>
                        <input name="quantity" 
                            class= "<?php echo (!empty($data->errors->err_quantity))? 'is-invalid' : '';?>" 
                            id="quantity"
                            value="<?php echo $data->data->quantity ?? ($data->pdfData->quantity ?? '');?>"></input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_quantity ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="serials">Serials: <sup>*</label>
                        <input name="serials" 
                            class= "<?php echo (!empty($data->errors->err_serials))? 'is-invalid' : '';?>" 
                            id="serials"
                            value="<?php echo $data->data->serials ?? ($data->pdfData->serials ?? '');?>"></input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_serials ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="wko_status">Status: </label>
                        <select name="wko_status" 
                            class= "<?php echo (!empty($data->errors->err_wko_status))? 'is-invalid' : '';?>" 
                            id="wko_status"
                            value="">
                            <option value="<?php echo $data->pdfData->status ?? 'Upcoming';?>"><?php echo $data->pdfData->status ?? 'Upcoming';?></option>
                            <option value="In Progress">In Progress</option>
                            <option value="On Hold">On Hold</option>
                            <option value="To Be Built">To Be Built</option>
                            <option value="Upcoming">Upcoming</option>
                            <option value="Completed">Completed</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_wko_status ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="wko_delivery">Delivery: </label>
                        <input name="wko_delivery" 
                            class= "<?php echo (!empty($data->errors->err_wko_delivery))? 'is-invalid' : '';?>" 
                            id="wko_delivery"
                            value="<?php echo $data->data->wko_delivery ?? ($data->pdfData->transport ?? '');?>"></input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_wko_delivery ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="wko_notes">Notes: </label>
                        <textarea name="wko_notes" 
                            class= "<?php echo (!empty($data->errors->err_wko_notes))? 'is-invalid' : '';?>" 
                            id="wko_notes"
                            value="<?php echo $data->data->wko_notes ?? ($data->pdfData->notes ?? ''); ?>"><?php echo $data->data->wko_notes ?? ($data->pdfData->notes ?? ''); ?></textarea>
                        <span class="invalid-feedback"><?php echo $data->errors->err_wko_notes ?? '';?></span>
                    </div>
                    <div class="hidden">
                        <input name="fixings"
                                id="fixings"
                                value="<?= $data->pdfData->fixings ?>"></input>
                    </div>
                    <div class="hidden">
                        <input name="connectors"
                                id="connectors"
                                value="<?= $data->pdfData->connectors ?>">
                        </input>
                    </div>
                    <div class="form-group">
                        <label for="addAnother">Add Another:</label>
                        <input name="addAnother" 
                                type="checkbox"
                                id="addAnother"
                                value="1"/>
                    </div>

                    <input type="submit" 
                        class="btn" 
                        value="Submit">
                </form>
        </div>
    </div>
</section>

<?php 
echo '<BR>';
echo $data->data->waveguide_finish_id ?? '';

?>

<?php echo '<PRE>data->errs:'; print_r($data->errors ?? '');?><br>
<?php echo 'data->data:'; print_r($data->data??'');?><br>
<?php echo 'pdfData:'; print_r($data->pdfData??'');?><br>
<?php echo 'data->models:'; print_r($data->models??'');?><br>

<?php require APPROOT . '/views/inc/footer.php'; ?>