<?php require APPROOT . '/views/inc/header.php'; ?>

<a class="" href="javascript:history.go(-1)"><?php include APPROOT.'/views/components/icons/backicon.php'; ?>Back</a>

<div class="row">
    <div class="card card-body bg-light mt-2 text-center">
        <h1 class=" text-lg">NEED TO LOAD INFORMATION INTO FORM PROPERLY!!</h1>
        <h2>Edit Work Order</h2>
        <p>Edit a WKO from the database using the form below</p>
        <br>
        <?php if(empty($data->data)) : ?>
            <h2>No workorder found with that Id</h2>
        <?php else : ?>
        <form action="<?php echo URLROOT; ?>workorders/edit/<?php echo $data->data->work_order_id ?>" method="post">
                <div class="pt-5 row">
                <label for="wko">WKO: <sup>*</label>
                    <input 
                        type="text" 
                        name="wko" 
                        class= "<?php echo (!empty($data->errors->err_wko))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->wko;?>">
                    <span class="invalid-feedback"><?php echo $data->errors->err_wko;?></span>
                </div>
                <div class="pt-3">
                    <label for="avn">AVN: <sup>*</label>
                    <input 
                        name="avn" 
                        class= "<?php echo (!empty($data->errors->err_avn))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->avn;?>">
                    </input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_avn;?></span>
                </div>
                <div class="pt-3">
                    <label for="cab_model_id">Speaker Model: <sup>*</label>
                    <select 
                        name="cab_model_id" 
                        class= "<?php echo (!empty($data->errors->err_cab_model))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->product->cab_model_id;?>">
                        
                        <option value="<?php if(isset($data->data->product->cab_model_id)) {echo $data->data->product->cab_model_id;} else { echo '';} ?>"><?php if(isset($data->data->product->cab_model_id)) {echo $data->models[$data->data->product->cab_model_id-1]->name;} else { echo '';} ?></option>
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
                        <option value="<?php if(isset($data->data->product->finish_id)) {echo $data->data->product->finish_id;} else { echo '';} ?>"><?php if(isset($data->data->product->finish_id)) {echo $data->finishes[$data->data->product->finish_id-1]->name;} else { echo '';} ?></option>
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
                        <option value="<?php if(isset($data->data->product->grille_finish_id)) {echo $data->data->product->grille_finish_id;} else { echo '';} ?>"><?php if(isset($data->data->product->grille_finish_id)) {echo $data->data->grille_finish->name;} else { echo '';} ?></option>
                        <?php foreach($data->finishes as $finish) : ?>
                            <?php if($finish->type != 'Polyurethane' & $finish->type != 'Wood' & $finish->type != 'Weather Resistant') :?>
                                <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data->errors->err_grille_colour;?></span>
                </div>
                <div class="pt-3 waveguideSel <?php echo(empty($data->data->waveguide->name) ? 'hidden' :'block'); ?>">
                    <label for="waveguide_finish_id">Waveguide Colour: </label>
                    <select 
                        name="waveguide_finish_id" 
                        class= "<?php echo (!empty($data->errors->err_waveguide_colour))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->waveguide_finish_id;?>">
                        <option value="<?php if(isset($data->product->waveguide)) {echo $data->product->waveguide;} else { echo '';} ?>"><?php if(isset($data->product->waveguide)) {echo $data->data->waveguide->name;} else { echo ' - -';} ?></option>
                        <?php foreach($data->finishes as $finish) : ?>
                            <?php if($finish->type === 'Polyurethane') :?>
                                <option value="<?php echo $finish->id;?>"><?php echo $finish->name;?></option> 
                            <?php endif; ?>
                         <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data->errors->err_waveguide_colour;?></span>
                </div>
                <div class="pt-3">
                    <label for="connectors">Connectors: </label>
                    <input name="connectors" 
                        class= "<?php echo (!empty($data->errors->err_connectors))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->connectors;?>"></input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_connectors;?></span>
                </div>
                <div class="pt-3">
                    <label for="wheels">Wheels:</label>
                    <input name="wheels" 
                        class= "" 
                        value="<?php echo $data->data->wheels;?>"></input>
                </div>
                <div class="pt-3">
                    <label for="quantity_required">Quantity Required: <sup>*</label>
                    <input name="quantity_required" 
                        class= "<?php echo (!empty($data->errors->err_quantity_required))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->quantity_required;?>"></input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_quantity_required;?></span>
                </div>
                <div class="pt-3">
                    <label for="quantity_built">Quantity Built: </label>
                    <input name="quantity_built" 
                    class= "<?php echo (!empty($data->errors->err_quantity_built))? 'is-invalid' : '';?>" 
                    value="<?php echo $data->data->quantity_built;?>"></input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_quantity_built;?></span>
                </div>
                <div class="pt-3">
                    <label for="serials">Serials: <sup>*</label>
                    <input name="serials" 
                        class= "<?php echo (!empty($data->errors->err_serials))? 'is-invalid' : '';?>" 
                        value="<?php echo $data->data->serials;?>"></input>
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
                        value="<?php echo $data->data->wko_delivery;?>"></input>
                    <span class="invalid-feedback"><?php echo $data->errors->err_wko_delivery;?></span>
                </div>
                <div class="pt-3">
                    <label for="wko_notes">Notes: </label>
                    <textarea name="wko_notes" 
                        class= "<?php echo (!empty($data->errors->err_wko_notes))? 'is-invalid' : '';?>" 
                        value="<?php echo($data->data->wko_notes); ?>"><?php echo($data->data->wko_notes); ?></textarea>
                    <span class="invalid-feedback"><?php echo $data->errors->err_wko_notes;?></span>
                </div>

                <input type="submit" 
                    class="btn" 
                    value="Submit">
            </form>
            <?php endif; ?>
    </div>
</div>

<pre>
    <?php print_r($data); ?>
</pre>

<?php require APPROOT . '/views/inc/footer.php'; ?>