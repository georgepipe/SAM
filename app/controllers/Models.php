<?php 
class Models extends Controller {
     private $moModel;
    // private $userModel;
    
    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }  
    $this->moModel = $this->model('Model');

    }

    public function vmodel($modelID) {

       $modelInformation = $this->moModel->getModelFromMid($modelID);

       $this->view('models/viewm', $modelInformation); 


    } 
}