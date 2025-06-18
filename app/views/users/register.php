<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5 text-center">
            <h2>Create an account</h2>
            <p>Please fill this out to register an account</p>
            <form action="<?php echo URLROOT; ?>/users/register" method="post">
            <div class="form-group">
                <label for="name">Name: <sup>*</label>
                <input type="text" name="name" class= "form-control form-control-lg <?php echo (!empty($data
                ['err_name']))? 'is-invalid' : '';?>" value="<?php echo $data['name'];?>">
                <span class="invalid-feedback"><?php echo $data['err_name'];?></span>
            </div>
            <div class="form-group">
                <label for="email">Email: <sup>*</label>
                <input type="email" name="email" class= "form-control form-control-lg <?php echo (!empty($data
                ['err_email']))? 'is-invalid' : '';?>" value="<?php echo $data['email'];?>">
                <span class="invalid-feedback"><?php echo $data['err_email'];?></span>
            </div>
            <div class="form-group">
                <label for="password">Password: <sup>*</label>
                <input type="password" name="password" class= "form-control form-control-lg <?php echo (!empty($data
                ['err_password']))? 'is-invalid' : '';?>" value="<?php echo $data['password'];?>">
                <span class="invalid-feedback"><?php echo $data['err_password'];?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm password: <sup>*</label>
                <input type="password" name="confirm_password" class= "form-control form-control-lg <?php echo (!empty($data
                ['err_confirm_password']))? 'is-invalid' : '';?>" value="<?php echo $data['confirm_password'];?>">
                <span class="invalid-feedback"><?php echo $data['err_confirm_password'];?></span>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <input type="submit" value="Register" class="btn btn-success btn-block">
                </div>
                <div class="col">
                    <a href="<?php echo URLROOT;?>users/login" class="btn-light btn-dark">Have an account? Login</a>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>