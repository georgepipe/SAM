<?php 
class Inventory extends Controller {
    private $woModel;
    private $userModel;
    
    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }   
    }

    public function index() {
        $data = [
            'title' => 'Inventory'
        ];
    
        $this->view('inventory/index', $data);
        }

}