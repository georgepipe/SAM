<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row" data-session="BOOO">
    <div class="grid col-md-6 mx-auto justify-center">
        <div class="mt-5 text-center">
            <h1 class="pb-4"> Speaker Assembly Managment System: SAMS</h1>
            <h2 class="p-4">Login</h2>
            <form action="<?php echo URLROOT; ?>users/login" method="post">
                <div class="form-group p-4">
                    <label for="email">Email: <sup>*</label>
                    <input 
                        type="email" 
                        name="email" 
                        class= " <?php echo (!empty($data['errors']->err_email))? 'is-invalid' : '';?>" 
                        value="<?php echo $data['data']->email;?>">
                    <span class="invalid-feedback"><?php echo $data['errors']->err_email;?></span>
                </div>
                <div class="form-group p-4">
                    <label for="password">Password: <sup>*</label>
                    <input 
                        type="password" 
                        name="password" 
                        class= " <?php echo (!empty($data['errors']->err_password)) ? 'is-invalid' : '';?>" 
                        value="">
                    <span class="invalid-feedback"><?php echo $data['errors']->err_password;?></span>
                </div>

                <div class="flex-row mt-3">
                    <div class="col">
                        <input type="submit" value="Login" class="btn">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>