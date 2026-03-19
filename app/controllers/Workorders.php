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
            // 'fixings' => trim($_POST['fixings']) ?? '',
            'connectors' => $_POST['connectors'] ?? '',
            'wheels' => $_POST['wheels'] ?? '0',
            'quantity_required' => (int)$_POST['quantity_required'],
            'serials' => trim($_POST['serials']) ?? '',
            'wko_status' => trim($_POST['wko_status']),
            'wko_delivery' => trim($_POST['wko_delivery']),
            'wko_notes' => trim($_POST['wko_notes']),
            'pid' => '',
            'addAnother' => $_POST['addAnother'] ?? ''
        ];
    }

    public function index($arg = '') {
        // echo 'the arguments are: '.$arg;
        // die(' [index stuff] ');
        $products = (object)[];
        $activeWorkorders = $this->woModel->getActiveOrders();
        $completedWorkorders = $this->woModel->getCompletedOrders();

        foreach($activeWorkorders as $workorder) {
            $acWkoCount = $this->woModel->getActiveWorkorderCount();
            $coWkoCount = $this->woModel->getCompletedWorkorderCount();
            $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
            $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
            $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->cab_finish_id);
            $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id);
            $workorder->waveguide = $this->woModel->getFinishfromId($workorder->product->waveguide);
            $workorder->pdesc = $this->poModel->createProductDescription($workorder);
            unset($workorder->product);
            unset($workorder->model);
        } 

        foreach($completedWorkorders as $workorder) {
            $acWkoCount = $this->woModel->getActiveWorkorderCount();
            $coWkoCount = $this->woModel->getCompletedWorkorderCount();
            $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
            $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
            $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->cab_finish_id);
            $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id);
            $workorder->waveguide = $this->woModel->getFinishfromId($workorder->product->waveguide);
            $workorder->pdesc = $this->poModel->createProductDescription($workorder);
            unset($workorder->product);
            unset($workorder->model);
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
            $data = $this->getPostedWorkorderData();

            $explodedGrille = explode(' ',$data->grille_finish_id);
            // print_r($explodedGrille);
            // die('grille check?');
            $data->grille_finish_id = $explodedGrille[0];
          
            $errors = (object) array (
                'err_wko' => '',
                'err_avn' => '',
                'err_cab_model_id' => '',
                'err_cab_finish_id' => '',
                'err_connectors' => '',
                'err_quantity_required' => '',
                'err_wko_status' => '',
                'err_serials' => ''
            );

            $data = (object) array ( 
                'data' => $data,
                'errors' => $errors
            );
            $fileName = 'AVN_'.str_pad($data->data->avn, 5 ,'0', STR_PAD_LEFT).'.pdf';
            $rules = new WorkorderRuleService();
        //validate post data//
            
         //search for WKO in the db //
            if($this->woModel->getWorkorderByWko($data->data->wko)) {
                if (!$data->data->wko == '') {
                    $data->errors->err_wko = 'A work order already exists with this WKO';
                }
            }
            
         //search for avn in the db //
            if($this->woModel->getWorkorderByAvn($data->data->avn) ) {
                if(!$data->data->avn == '') {
                    $data->errors->err_avn = 'A work order already exists with this AVN';
                }
            } 
        //get finish ID from PDF string //
            if(!is_numeric($data->data->cab_finish_id) && !empty($data->data->cab_finish_id)) {
                $sh = preg_match('/(SH)/',$data->data->cab_model_id);
                $data->data->cab_finish_id = $this->woModel->getFidFromName($data->data->cab_finish_id, !empty($sh));
            }
        //check for empty model field, then retreive model ID from string if field is NaN//
            if(empty($data->data->cab_model_id)) {
                $data->errors->err_cab_model = 'Please select the cabinet model';
            } elseif (!is_numeric($data->data->cab_model_id)) {
                $data->data->cab_model_id = $this->moModel->getMidFromName($data->data->cab_model_id);
            }
        //check for empty cabinet finish field//
            if(empty($data->data->cab_finish_id) && empty($data->data->waveguide_finish_id)) {
                $data->errors->err_cab_colour = 'Please select the cabinet finish';
            } else {$data->errors->err_cab_colour = '';}

        //get waveguide finish ID from string //
            if(!is_numeric($data->data->waveguide_finish_id) && !empty($data->data->waveguide_finish_id)) {
                $sh = preg_match('/(SH)/',$data->data->cab_model_id);
                $data->data->waveguide_finish_id = $this->woModel->getFidFromName($data->data->waveguide_finish_id, isset($sh) ? true : false);
            }
        //check for grille finish requirment 
            $defaultGrilleColour = $rules->defaultGrilleFinish($data->data->cab_model_id);
            if(empty($data->data->grille_finish_id) && $defaultGrilleColour != null) {
                $data->data->grille_finish_id = $defaultGrilleColour;
            }
            if(empty($data->data->grille_finish_id)) { 
                if($rules->requiresConnectorSelection($data->data->cab_model_id)) {
                    $data->errors->err_grille_colour = 'Please select a grille colour';
                } else {$data->errors->err_grille_colour = '';}
            };

        //get grille finish id from string if NaN
            if (!is_numeric($data->data->grille_finish_id) && !empty($data->data->grille_finish_id)) {
                $data->data->grille_finish_id = $this->woModel->getFidFromName($data->data->grille_finish_id, 0); 
            }
            
        //connectors!
            //check and set default if exists
            $defaultConnectors = $rules->defaultConnectors($data->data->cab_model_id);
            if(empty($data->data->connectors) && $defaultConnectors != null) {
                $data->data->connectors = $defaultConnectors;
            }
            //if empty and req set error
            if(empty($data->data->connectors)) {
                if($rules->requiresConnectorSelection($data->data->cab_model_id)) {
                    $data->errors->err_connectors = "Please select a connector type";
                }
            }
        //SERIALS
            //validate serial string
            if(preg_match('/([A-z])/',$data->data->serials)) {
                $data->errors->err_serials = "Serials cannot contain characters";
            }
            //set TBC for empty serial field
            if(empty($data->data->serials)) {
                $data->data->serials = 'To Be Confirmed';
            } else {
                //check serials!
                $duplicates = $this->seModel->addSerials($data->data->work_order_id, $data->data->cab_model_id, $data->data->serials, 1);
                if(isset($duplicates[0]->sid)) {
                    //there are duplicate serials
                    $data->errors->err_serials = 'Duplicate serials detected: ';
                    foreach($duplicates as $duplicate) {
                        $workorder = $this->woModel->getWorkorderById($duplicate->work_order_id);
                        $data->errors->err_serials = $data->errors->err_serials.'AVN: '.$workorder->avn.' Serial ->'.$duplicate->serial_num.' :';
                    }
                } 
            }
            
        //validate quantity exists & that qty of provided serials matches qty of cabs
            if(empty($data->data->quantity_required)) {
                $data->errors->err_quantity_required = 'Please enter a quantity';
            } else {
                if(empty($data->errors->err_serials) && !$data->data->serials === 'To Be Confirmed') {
                    $sCount = $this->seModel->countSerials($data->data->serials);
                    // echo $data->data->serials;
                    // echo '<BR>'.$sCount;
                    if($data->data->quantity_required != $sCount && !empty($data->data->serials)) {
                        $data->errors->err_quantity_required = 'Quantity is '.$data->data->quantity_required.' but '.$sCount.' serial(s) has/have been specified.';
                    }
                }
            } 
        //check if waveguide finish is req
            if(empty($data->data->waveguide) && !empty($data->cab_model_id)) {
                $data->errors->err_waveguide_colour = match ($data->cab_model_id) {
                //this needs refactoring - current static setup is not growth friendly
                38,39,40,41,42,43,44,45,46,47,48,49,50,52,53,54,55,56,57 => 'Please select a waveguide colour',
                default => ''
                };
            };


            $pid = $this->woModel->getPidFromOptions($data->data); 
            $data->data->pid = $pid;
            // if(empty($pid)&&empty($data->data->fixings)) { //set default fixings for new pids
            //     echo '<pre>';
            //     print_r($data->data);
            //     echo '<BR>';
            //     print_r($pid);
            //     die('PID?');
            // } 
            //todo: serial checker
        // check for errors
        
            $errors = '';
            foreach($data->errors as $error) {
                if($error != '') {
                    $errors = 'TRUE';
                }
            };

            if (empty($errors)) {
        //validated
                //$pid = $this->woModel->getPidFromOptions($data->data);
                // echo '<PRE>';
                // print_r($data);
                // die('');
                if (empty($pid)) {
                    // echo '<PRE>';
                    // $data->data->model = $this->moModel->getModelFromMid($data->data->cab_model_id);
                    // $this->poModel->addPidFromOptions($data->data);
                    // $pid = $this->woModel->getPidFromOptions($data->data);
                    // $data->data->pid = $pid;
                    empty($pid) ? throwErr(999,'PID ERROR: Seek guidance from the almighty flying spaghetti monster'): '';
                }
            //save to db
                if ($this->woModel->addOrder($data->data)){
                    // $this->seModel->addSerials($data->data->work_order_id, $data->data->cab_model_id, $data->data->serials);
                    $pdfLoc = TEMPDIR.$fileName;
                    $pdfDest = AVNDIR.$fileName;
                    $file = rename($pdfLoc, $pdfDest);
                    flash('post_message', 'Workorder sucessfully added');
                    if($data->data->addAnother == 1) {
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
            if (!empty($data->data) && !empty($data->data->cab_model_id)) {
                $data->data->product = $this->poModel->getProductFromPid($data->data->pid);
                // $data->data->cab_finish = $this->woModel->getFinishfromId($data->product->finish_id);
                // $data->data->grille_finish = $this->woModel->getFinishfromId($data->product->grille_finish_id);
                $data->data->waveguide = $this->woModel->getFinishfromId($data->product->waveguide);
            };
            $data = (object) [
                
                'finishes' => $finishes,
                'errors' => $data->errors,
                'models' => $models,
                'data' => $data->data
            ];
            // echo '<PRE>';
            // print_r($data);
            // die('<BR>we dyin');
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
               $data = (object) array (
                'work_order_id' => $id,
                'wko' => trim($_POST['wko']),
                'avn' => trim($_POST['avn']),
                'cab_model_id' => trim($_POST['cab_model_id']),
                'cab_finish_id' => trim($_POST['cab_finish_id']),
                'waveguide_finish_id' => trim($_POST['waveguide_finish_id']),
                'grille_finish_id' => trim($_POST['grille_finish_id']),
                'connectors' => trim($_POST['connectors']),
                'wheels' => $_POST['wheels'],
                'quantity_required' => (int)$_POST['quantity_required'],
                'serials' => trim($_POST['serials']),
                'wko_status' => trim($_POST['wko_status']),
                'wko_delivery' => trim($_POST['wko_delivery']),
                'wko_notes' => trim($_POST['wko_notes']),
                'pid' => ''
            );
   
               $errors = (object) array (
                   'err_wko' => '',
                   'err_avn' => '',
                   'err_cab_model_id' => '',
                   'err_cab_finish_id' => '',
                   'err_connectors' => '',
                   'err_quantity_required' => '',
                   'err_wko_status' => '',
                   'err_serials' => ''
               );
   
               $data = (object) array ( 
                   'data' => $data,
                   'errors' => $errors
               );
                //validate post data//
            //    if(empty($data->data->wko)) {
            //        $data->errors->err_wko = 'Please enter work order reference';
            //    }
            //    if(empty($data->data->avn)) {
            //        $data->errors->err_avn = 'Please enter advice note reference';
            //    }
               if(empty($data->data->cab_model_id)) {
                   $data->errors->err_cab_model = 'Please select the cabinet model';
               }
               if(empty($data->data->cab_finish_id) && empty($data->data->waveguide_finish_id)) {
                $data->errors->err_cab_colour = 'Please select the cabinet finish';
            } 
               if(empty($data->data->quantity_required)) {
                   $data->errors->err_quantity_required = 'Please enter a quantity';
               }
               if(empty($data->data->serials)) {
                   $data->data->serials = 'To Be Confirmed';
               }

               $data->data->pid = $this->woModel->getPidFromOptions($data->data); 
            //    echo $data->pid;
            //    die('osahdoihas');
           //if number of serials doesnt match quantity throw error
               //todo
           // check for errors
               $errors = '';
                   foreach($data->errors as $error) {
                   if($error != '') {
                       $errors = 'TRUE';
                   }
               }
               if (!$errors === TRUE) {
           // if(empty($data['errors']->err_avn) && empty($data['errors']->err_wko) && empty($data['errors']->err_cab_model) && empty($data['errors']->err_cab_colour) && empty($data['errors']->err_quantity_required)) {
             //if validated run the edit method
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
            if(!empty($data->data->product->waveguide)) {
            $data->data->waveguide = $this->woModel->getFinishfromId($data->data->product->waveguide);}
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
                if(!empty($data->data->product->waveguide)) {
                $data->data->waveguide = $this->woModel->getFinishfromId($data->data->product->waveguide);}
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
        //load workorder into data
        $debug = [];
        $workorder = $this->woModel->getWorkorderById($id);
        $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
        $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
        $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->cab_finish_id);
        $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id);
        $workorder->waveguide = $this->woModel->getFinishfromId($workorder->product->waveguide);
        $workorder->pdesc = $this->poModel->createProductDescription($workorder);
        //unset($workorder->model);

        $data = (object) array (
            'workorder' => $workorder
        );
        //load page
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
        $newQuantity = $originalWorkorder->quantity_required - $sPoint; 
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
        unset($newWorkorder->created_at);
        unset($newWorkorder->updated_at);
        unset($newWorkorder->work_order_id);
        $newWorkorder->serials = $secondHalf;
        $newWorkorder->wko_notes = $newWorkorder->wko_notes.'Order split on '.$todaysDate; 
        $originalWorkorder->wko_notes = 'Order split on '.$todaysDate;
        $originalWorkorder->quantity_required = $sPoint;
        $newWorkorder->quantity_required = $newQuantity;
        // print_r($originalWorkorder);
        // print_r($newWorkorder);
        $this->woModel->editOrder($originalWorkorder);
        $this->woModel->addOrder($newWorkorder);
        redirect('workorders/index');
    }

}
