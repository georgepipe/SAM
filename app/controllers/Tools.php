<?php 
class Tools extends Controller {

    private $tModel;

    public function __construct() {
        $this->tModel = $this->model('Tool');
         if(!isLoggedIn()) {
               // and if user is not george
            redirect('users/login');
        }  

    }


   public function index() {
    $data = [];
    $this->view("/tools/index", $data);
    
   }
    
   public function addP() {
    //add new product to the finished product table
    $data = [];
    $this->view("/tools/addP", $data);
   }

   public function addCab() {
    //add new cabinet to cabinets table
    $data = [];
    $this->view("/tools/addCab", $data);
   }

   public function addF() {
    //add Finish to the finishes table and link it with relavent materials
    $data = [];
    $this->view("/tools/addF", $data);
   }

   public function addComp() {
    //add component to the component table and link it with relavent product(s)
    $data = [];
    $this->view("/tools/addComp", $data);
   }

   public function addSA() {
    //add subassembly to the subassembly table and link it with relavent product(s)
    $data = [];
    $this->view("/tools/addSA", $data);
   }

   public function addD() {
    //add driver to the drivers table and link it with relavent product(s)
    $data = [];
    $this->view("/tools/addD", $data);
   }

   public function editMComp() {
    //show component, drivers and subassemblies linked to model. allow edit and save changes
    $data = [];
    $this->view("/tools/editMComp", $data);
   }
}