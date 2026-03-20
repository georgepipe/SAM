<?php require APPROOT . '/views/inc/header.php'; ?>

<a class="" href="javascript:history.go(-1)"><?php include APPROOT.'/views/components/icons/backicon.php'; ?>Back</a>

<section>
    <div class="fade-in editform">
        <div class="grid justify-center text-center w-lvw">
            <h1 class="">Edit Work Order</h1>
            <BR>
            <p style="width:600px">Edit a WKO from the database using the form below</p>
            <br>
            <?php if(empty($data->data)) : ?>
                <h2>No workorder found with that Id</h2>
            <?php else : ?>
            <div class="avnPdfDiv">
                <a class="avnPdf" href="#" onclick="AVNwindow=window.open('<?php echo URLROOT?>advice_notes/AVN_<?php echo str_pad($data->data->avn, 5,'0', STR_PAD_LEFT).'.pdf'?>', 'AVNwindow', 'width=400, height=600');">Original AVN</a>
            </div>
            <form class="input-form" action="<?php echo URLROOT; ?>workorders/edit/<?php echo $data->data->work_order_id ?>" method="post">
                    <div class="form-group">
                        <label for="wko">WKO: </label>
                        <input 
                            id="wko"
                            type="text" 
                            name="wko" 
                            class= "<?php echo (!empty($data->errors->err_wko))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->wko;?>">
                        <span class="invalid-feedback"><?php echo $data->errors->err_wko ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="avn">AVN: </label>
                        <input 
                            id="avn"
                            name="avn" 
                            class= "<?php echo (!empty($data->errors->err_avn))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->avn;?>">
                        </input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_avn ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="cab_model_id">Speaker Model: </label>
                        <select 
                            id="cab_model_id"
                            name="cab_model_id" 
                            class= "cabSel <?php echo (!empty($data->errors->err_cab_model))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->product->cab_model_id;?>">
                            
                            <option value="<?php if(isset($data->data->product->cab_model_id)) {echo $data->data->product->cab_model_id;} else { echo '';} ?>"><?php if(isset($data->data->product->cab_model_id)) {echo $data->models[$data->data->product->cab_model_id-1]->name;} else { echo '';} ?></option>
                            <?php foreach($data->models as $model) : ?>
                                <option value="<?php echo $model->name;?>"><?php echo $model->name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_cab_model ?? '';?></span>
                    </div>
                    <div class="ccDiv form-group">
                        <label for="cab_finish_id">Speaker Colour: </label>
                        <select 
                            id="cab_finish_id"
                            name="cab_finish_id" 
                            class= "<?php echo (!empty($data->errors->err_cab_colour))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->cab_finish_id ?? '';?>">
                            <option value="
                                <?php if(isset($data->data->product->cab_finish_id)) 
                                    {echo $data->data->product->cab_finish_id;} 
                                        else { echo '';} ?>">
                                <?php if(isset($data->data->product->cab_finish_id)) 
                                    {echo $data->finishes[$data->data->product->cab_finish_id-1]->name;} 
                                        else { echo '';} ?>
                            </option>

                            <?php foreach($data->finishes as $finish) : ?>
                                <?php if($finish->type != 'Metal' & $finish->type != 'Polyurethane') :?>
                                    <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_cab_colour ?? '';?></span>
                    </div>
                    <div class="gDiv form-group">
                        <label for="grille_finish_id">Speaker Grille: </label>
                        <select 
                            id="grille_finish_id"
                            name="grille_finish_id" 
                            class= "<?php echo (!empty($data->errors->err_grille_colour)) ? 'is-invalid' : '';?> " 
                            value="<?php echo $data->data->grille_finish_id ?? '';?>">
                            <option value="<?php if(isset($data->data->product->grille_finish_id)) {echo $data->data->product->grille_finish_id;} else { echo '';} ?>"><?php if(isset($data->data->product->grille_finish_id)) {echo $data->finishes[$data->data->product->grille_finish_id-1]->name;} else { echo '';} ?></option>
                            <?php foreach($data->finishes as $finish) : ?>
                                <?php if($finish->type != 'Polyurethane' & $finish->type != 'Wood' & $finish->type != 'Weather Resistant') :?>
                                    <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_grille_colour ?? '';?></span>
                    </div>
                    <div class="wDiv <?php echo(empty($data->data->product->waveguide) ? 'hidden' :'form-group'); ?>">
                        <label for="waveguide_finish_id">Waveguide Colour: </label>
                        <select 
                            id="waveguide_finish_id"
                            name="waveguide_finish_id" 
                            class= "<?php echo (!empty($data->errors->err_waveguide_finish))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->product->waveguide->id ?? '';?>">
                            <option value="<?php if(isset($data->data->waveguide->id)) {echo $data->data->waveguide->id;} else { echo '';} ?>"><?php if(isset($data->data->waveguide->id)) {echo $data->data->waveguide->name;} else { echo '';} ?></option>
                            <?php foreach($data->finishes as $finish) : ?>
                                <?php if($finish->type === 'Polyurethane') :?>
                                    <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data->errors->err_waveguide_finish ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="connectors">Connectors: </label>
                        <input name="connectors" 
                            id="connectors"
                            class= "<?php echo (!empty($data->errors->err_connectors))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->product->connectors ?? '';?>"></input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_connectors ?? '';?></span>
                    </div>
                    <div class="whDiv form-group">
                        <label for="wheels">Wheels:</label>
                        <input name="wheels" 
                            id="wheels"
                            class="" 
                            value="<?php echo $data->data->wheels;?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity Required: </label>
                        <input name="quantity" 
                            id="quantity"
                            class= "<?php echo (!empty($data->errors->err_quantity))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->quantity ?? '';?>"></input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_quantity ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="serials">Serials: </label>
                        <input name="serials" 
                            id="serials"
                            class= "<?php echo (!empty($data->errors->err_serials))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->serials ?? '';?>"></input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_serials ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="wko_status">Status: </label>
                        <select name="wko_status" 
                            id="wko_status"
                            class= "<?php echo (!empty($data->errors->err_wko_status))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->wko_status ?? '';?>">
                            <option value=""><?php if(isset($data->data->wko_status)) {echo $data->data->wko_status;} else { echo '';} ?></option>
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
                            id="wko_delivery"
                            class= "<?php echo (!empty($data->errors->err_wko_delivery))? 'is-invalid' : '';?>" 
                            value="<?php echo $data->data->wko_delivery ?? '';?>"></input>
                        <span class="invalid-feedback"><?php echo $data->errors->err_wko_delivery ?? '';?></span>
                    </div>
                    <div class="form-group">
                        <label for="wko_notes">Notes: </label>
                        <textarea name="wko_notes" 
                            id="wko_notes"
                            class= "<?php echo (!empty($data->errors->err_wko_notes))? 'is-invalid' : '';?>" 
                            value="<?php echo($data->data->wko_notes ?? ''); ?>"><?php echo($data->data->wko_notes ?? ''); ?></textarea>
                        <span class="invalid-feedback"><?php echo $data->errors->err_wko_notes ?? '';?></span>
                    </div>
                    <div class="form-group submitBtn">
                    <input type="submit" 
                        id="submit" 
                        class="btn submitbtn" 
                        value="Submit">
                    </div>
            </form>

            <?php endif; ?>
        </div>
    </div>

</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>