<?php 
class Model extends Controller {
     private $moModel;
    // private $userModel;
    
    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }  
        $this->moModel = $this->model('Model');

    }

  
}