<?php
class Transport extends Controller {

    private $tnModel;
    private $woModel;
    private $moModel;
    private $poModel;

    public function __construct() { 
        $this->tnModel = $this->model('Transportnote');
        $this->woModel = $this->model('Workorder');
        $this->moModel = $this->model('Model');
        $this->poModel = $this->model('Product');
         if(!isLoggedIn()) {
            redirect('users/login');
        }  
    }


    public function index() {
        $activetransportnotes = $this->tnModel->getActiveTransportNotes();
        $assignedworkorders = $this->woModel->getWorkOrdersWithTransportNotes();
        $avaliableworkorders = $this->woModel->getAvaliableWorkOrdersForTransport();
        $completedtransportnotes = $this->tnModel->getCompletedTransportNotes();
        foreach($avaliableworkorders as $workorder) {
            $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
            $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
            $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->finish_id);
            $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id);
            $workorder->waveguide = $this->woModel->getFinishfromId($workorder->product->waveguide);
            $workorder->pdesc = $this->poModel->createProductDescription($workorder);
            unset($workorder->product);
            unset($workorder->model);
            unset($workorder->cab_finish);
            unset($workorder->grille_finish);
            unset($workorder->waveguide);
        } 
        
        $data = (object) array (
            'activetransportnotes' => $activetransportnotes,
            'assignedworkorders' => $assignedworkorders,
            'avaliableworkorders' => $avaliableworkorders,
            'completedtransportnotes' => $completedtransportnotes
        );
        //print_r($transportnotes); //debug point
        $this->view('transport/index', $data);
    }

    public function viewtn($tnid){
        $transportnote = $this->tnModel->getTransportNoteFromId($tnid);
        $workorders = $this->woModel->getWorkOrdersFromTransportNote($tnid);
        $models = $this->moModel->getModelsForTNs($workorders);
        // $models = [];
        foreach($workorders as $workorder) {
            $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
            $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
            $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->finish_id);
            $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id);
            $workorder->waveguide = $this->woModel->getFinishfromId($workorder->product->waveguide);
            $workorder->pdesc = $this->poModel->createProductDescription($workorder);
            unset($workorder->product);
            unset($workorder->model);
            unset($workorder->cab_finish);
            unset($workorder->grille_finish);
            unset($workorder->waveguide);
        } 
        $data = (object) array (
            'transportnote' => $transportnote,
            'workorders' => $workorders
        );
        $this->view('transport/viewtn', $data);
    }

    public function tnote ($transportType, $wkos) {
        $transportType = $transportType === 'd' ? 'Delivery' : 'Collection';
        $wkos = str_getcsv($wkos,",");
        $tData = [
            'transport' => $transportType,
            'wkos' => $wkos
        ];
        print_r($wkos);
         if($wkos[0]>0) {
        $lastInsertedId = $this->tnModel->addTransportNote($tData);
            //reload index
            //update wkos with tnid
            $workorders = [];
            foreach ($wkos as $wko) {
                $workorder = $this->woModel->getWorkorderById($wko);
                $workorder->tnid = $lastInsertedId;
                print_r($workorder);
                $this->woModel->updateWorkorderTnid($workorder);
            }
            redirect('transport/index');  
        } else {
            die('OH SHIT! ERR No Wko(s) set..');
        };

    }

    public function delete($tnid) {
    //remove tnid from associated wkos duh
    echo '<pre>';
        $Wkos = $this->woModel->getWorkOrdersFromTransportNote($tnid);
        print_r($Wkos);
        foreach ($Wkos as $workorder) {
            $this->woModel->removeTnid($workorder);
        }
        
    //delete TNID duh!
        if($this->tnModel->deleteTnote($tnid)) {
            return true;
        } else {
            return false;
        }
    }

    public function complete($tnid) {
        //update as complete innit
        if($this->tnModel->completeTnote($tnid)) {
            return true;
        } else {
            return false;
        } 
    }
}