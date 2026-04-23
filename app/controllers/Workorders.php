<?php 
/**
 * Controls the front end php functions required by the work orders to get,
 * add, edit and delete orders as well as directing the browser to the relavent views
 */
class Workorders extends Controller {
    private $woModel;
    private $moModel;
    private $poModel;
    private $userModel;
    private $seModel;
    
    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        } 
        $this->moModel = $this->model('Model');
        $this->woModel = $this->model('Workorder');
        $this->poModel = $this->model('Product');
        $this->seModel = $this->model('Serial');
    }

    public function getPostedWorkorderData(){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
        return (object) [
            'wko' => trim($_POST['wko']),
            'avn' => trim($_POST['avn']),
            'cab_model_id' => trim($_POST['cab_model_id']),
            'cab_finish_id' => trim($_POST['cab_finish_id']),
            'waveguide_finish_id' => trim($_POST['waveguide_finish_id']),
            'grille_finish_id' => trim($_POST['grille_finish_id']),
            'connectors' => (string)$_POST['connectors'] ?? '',
            'wheels' => (bool)$_POST['wheels'] ?? '0',
            'quantity' => (int)$_POST['quantity'],
            'serials' => trim($_POST['serials']) ?? '',
            'wko_status' => trim($_POST['wko_status']),
            'wko_delivery' => trim($_POST['wko_delivery']),
            'wko_notes' => trim($_POST['wko_notes']),
            'pid' => '',
            'addAnother' => $_POST['addAnother'] ?? ''
        ];
    }

    public function initialiseErrors(){
        return (object) [
                'err_wko' => '',
                'err_avn' => '',
                'err_cab_model_id' => '',
                'err_cab_finish_id' => '',
                'err_waveguide_finish_id' => '',
                'err_grille_finish' => '',
                'err_connectors' => '',
                'err_quantity' => '',
                'err_serials' => '',
                'err_wko_status' => ''
        ];
    }
    

    public function index($arg = '') {
        $activeWorkorders = $this->woModel->getActiveOrders();
        $completedWorkorders = $this->woModel->getCompletedOrders();
        $acWkoCount = $this->woModel->getActiveWorkorderCount();
        $coWkoCount = $this->woModel->getCompletedWorkorderCount();

        $descriptionService = new ProductDescriptionService($this->moModel, $this->poModel, $this->woModel);
        foreach($activeWorkorders as $workorder) { 
            $workorder = $descriptionService->createProductDescription($workorder); 
        } 
        foreach($completedWorkorders as $workorder) {
            $workorder = $descriptionService->createProductDescription($workorder); 
        } 

        $models = $this->moModel->getModelNames();
        $finishes = $this->woModel->getFinishes();

        $data = [
            'activeWorkorders' => $activeWorkorders,
            'completedWorkorders' => $completedWorkorders,
            'acWkoCount' => $acWkoCount,
            'coWkoCount' => $coWkoCount
        ];

        // flash('post_message', 'Hello!');
        $this->view('workorders/index', $data);
    }

 
    public function add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && !isset(($_FILES['pdf']))) {
            $data = (object) [
                'form' => $this->getPostedWorkorderData(),
                'errors' => $this->initialiseErrors()
            ];
            
            $ruleService= new WorkorderRuleService($this->woModel, $this->moModel);
            $validationService = new WorkorderValidationService($this->woModel, $this->seModel, $ruleService);
            $data = $ruleService->apply($data);
            $data = $validationService->validate($data);
            $errors = $validationService->hasErrors($data->errors);
            
            if (!$errors) {
            // validated
                if (empty($data->form->pid)) {
                    // dumpAndDie($data);
                    empty($data->form->pid) ? throwErr(999,'PID ERROR: Contact system admin'): '';
                }
            //save to db
                // dumpAndDie($data->form);
                if ($this->woModel->addOrder($data->form)){
                    // $this->seModel->addSerials($data->form->work_order_id, $data->form->cab_model_id, $data->form->serials);
                    $fileName = 'AVN_'.str_pad($data->form->avn, 5 ,'0', STR_PAD_LEFT).'.pdf';
                    $pdfLoc = TEMPDIR.$fileName;
                    $pdfDest = AVNDIR.$fileName;
                    $file = rename($pdfLoc, $pdfDest);
                    flash('post_message', 'Workorder sucessfully added');
                    if($data->form->addAnother == 1) {
                        redirect('workorders/add');
                    } else {
                        redirect('workorders/index');    
                    }
                    
                } else {
                    die ('something went wrong');
                }
            } else {
                //reload view with errors
                $models = $this->moModel->getModelNames();
                $finishes = $this->woModel->getFinishes();
                if (!empty($data->form) && !empty($data->form->cab_model_id)) {
                    $data->form->product = $this->poModel->getProductFromPid($data->form->pid);
                    $data->form->waveguide = $this->woModel->getFinishfromId($data->product->waveguide_finish_id);
                };
                $data = (object) [
                    
                    'finishes' => $finishes,
                    'errors' => $data->errors,
                    'models' => $models,
                    'data' => $data->form
                ];
                $this->view('workorders/add', $data);
            }

        }   else {
            $models = $this->moModel->getModelNames();
            $finishes = $this->woModel->getFinishes();
            $data = (object) [
                'models' => $models,
                'finishes' => $finishes
            ];
            $this->view('workorders/add', $data);
        }
    }

    public function edit($id = 0) {
        //if post then filter data and validate
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //sanitise POST array
               $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
               $form = (object) array (
                'work_order_id' => $id,
                'wko' => trim($_POST['wko']),
                'avn' => trim($_POST['avn']),
                'cab_model_id' => trim($_POST['cab_model_id']),
                'cab_finish_id' => trim($_POST['cab_finish_id']),
                'waveguide_finish_id' => trim($_POST['waveguide_finish_id']),
                'grille_finish_id' => trim($_POST['grille_finish_id']),
                'connectors' => trim($_POST['connectors']),
                'wheels' => $_POST['wheels'],
                'quantity' => (int)$_POST['quantity'],
                'serials' => trim($_POST['serials']),
                'wko_status' => trim($_POST['wko_status']),
                'wko_delivery' => trim($_POST['wko_delivery']),
                'wko_notes' => trim($_POST['wko_notes']),
                'pid' => ''
            );
   
               $errors = $this->initialiseErrors();
   
               $data = (object) array ( 
                   'form' => $data,
                   'errors' => $errors
               );
                //validate post data//
                $ruleService= new WorkorderRuleService($this->woModel, $this->moModel);
                $validationService = new WorkorderValidationService($this->woModel, $this->seModel, $ruleService);
                $data = $ruleService->apply($data);
                $data = $validationService->validate($data);
                $errors = $validationService->hasErrors($data->errors);

               
            if (!$errors) {
                if ($this->woModel->editOrder($data->data)){
                    flash('post_message', 'Workorder Updated');
                    redirect('workorders/index');
                } else {
                    die ('<br> something went wrong');
                };
            } else {
            //else reload with errors
                $data = (object) [
                    'models' => $this->moModel->getModelNames(),
                    'finishes' => $this->woModel->getFinishes(),
                    'data' => $this->woModel->getWorkorderById($id),
                    'errors' => $data->errors
                ];
                if (!empty($data->data)) {
                    $data->data->product = $this->poModel->getProductFromPid($data->data->pid);
                    if(!empty($data->data->product->waveguide_finish_id)) {
                    $data->data->waveguide_finish_id = $this->woModel->getFinishfromId($data->data->product->waveguide_finish_id);}
                };
                    $this->view('workorders/edit', $data);
            }
        }  else { //not a post request
            if ($id === 0) { //no order ID
                redirect('workorders/index'); //send to index
            } else {
         //else get workorder from model
            $data = (object) [
                'models' => $this->moModel->getModelNames(),
                'finishes' => $this->woModel->getFinishes(),
                'data' => $this->woModel->getWorkorderById($id),
            ];
            if (!empty($data->data)) {
                $data->data->product = $this->poModel->getProductFromPid($data->data->pid);
                if(!empty($data->data->product->waveguide_finish_id)) {
                $data->data->waveguide_finish_id = $this->woModel->getFinishfromId($data->data->product->waveguide);}
            };
         //load edit page with workorder data 
            $this->view('workorders/edit', $data);
            };
        }
    }


    public function delete($id) {
        if($this->woModel->deleteOrder($id)) {
            return true;
        } else {
            return false;
        }
    }

    public function viewwo($id) {
        $descriptionService = new ProductDescriptionService($this->moModel, $this->poModel, $this->woModel);
        $workorder = $descriptionService->createProductDescription($this->woModel->getWorkorderById($id));
        $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
        $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
        if(!empty($workorder->product->cab_finish_id)) {$workorder->product->cab_finish = $this->woModel->getFinishFromId($workorder->product->cab_finish_id); unset($workorder->product->cab_finish_id);}
        if(!empty($workorder->product->grille_finish_id)) {$workorder->product->grille_finish_id = $this->woModel->getFinishFromId($workorder->product->grille_finish_id);}
        if(!empty($workorder->product->waveguide_finish_id)) {$workorder->product->waveguide_finish_id = $this->woModel->getFinishFromId($workorder->product->waveguide_finish_id);}

        $data = (object) array (
            'workorder' => $workorder
        );
        
        $this->view('workorders/viewwo', $data);
    }

    public function complete($work_order_id, $serialRanges = '') {
        if(!empty($serialRanges)) {
            //add serials to the work order first!
            $this->woModel->setSerials($work_order_id, $serialRanges);
        }
        $workorder = $this->woModel->getWorkorderById($work_order_id);
        $product = $this->poModel->getProductFromPid($workorder->pid);
        $this->seModel->addSerials($work_order_id, $product->cab_model_id, $workorder->serials);
        if ($this->woModel->completeOrder($workorder)) {
            flash('post_message', 'Work Order marked as completed!');
        } else { die('Something went horribly horribly wrong, please contact the web admin'); }
        redirect('workorders/index');
        
    
    }

    public function split($work_order_id, $sPoint) {
        $todaysDate = date('m/d/Y', time());

        $originalWorkorder = $this->woModel->getWorkorderById($work_order_id);

        $newQuantity = $originalWorkorder->quantity - $sPoint; 
        $sCount = $this->seModel->expandSerials($originalWorkorder->serials);
        $firstHalf = array_slice($sCount,0, $sPoint); 
        $secondHalf = array_slice($sCount,$sPoint, );

        //contract serials again before saving back to original WKO and creating new WKO
        $firstHalf = $this->seModel->contractSerials($firstHalf);
        $secondHalf = $this->seModel->contractSerials($secondHalf);
        //echo 'first half: '.$firstHalf.'<BR>';
        //echo 'second half: '.$secondHalf.'<BR>';
        $newWorkorder = unserialize(serialize($originalWorkorder));
        $originalWorkorder->serials = $firstHalf;
        $newWorkorder->serials = $secondHalf;
        
        $newWorkorder->wko_notes = $newWorkorder->wko_notes.'Order split on '.$todaysDate; 
        $originalWorkorder->wko_notes = 'Order split on '.$todaysDate;
        $originalWorkorder->quantity = $sPoint;
        $newWorkorder->quantity = $newQuantity;

        unset($newWorkorder->created_at);
        unset($newWorkorder->work_order_id);
        $this->woModel->editOrder($originalWorkorder);
        $this->woModel->addOrder($newWorkorder);
        redirect('workorders/index');
    }

}
