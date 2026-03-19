<?php 

    class Api extends Controller {
        private $woModel;
        private $poModel;
        private $moModel; 

        public function __construct() {
            //API VALIDATION GOES HERE
            $this->moModel = $this->model('Model');
            $this->woModel = $this->model('Workorder');
            $this->poModel = $this->model('Product');
        }

        public function workorder($type = '', $page = '') {
            
            if ($type === 'active') {
                $activeWorkorders = $this->woModel->getActiveOrders($page);
                foreach($activeWorkorders as $workorder) {
                    $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
                    $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
                    $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->finish_id);
                    $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id);
                    $workorder->waveguide = $this->woModel->getFinishfromId($workorder->product->waveguide);
                    $workorder->pdesc = $this->poModel->createProductDescription($workorder);
                    unset($workorder->product);
                    unset($workorder->model);
                    $json_data = json_encode((object) $activeWorkorders);
                } 
            } elseif ($type === 'completed') {
                $completedWorkorders = $this->woModel->getCompletedOrders($page);
                foreach($completedWorkorders as $workorder) {
                    $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
                    $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
                    $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->finish_id);
                    $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id);
                    $workorder->waveguide = $this->woModel->getFinishfromId($workorder->product->waveguide);
                    $workorder->pdesc = $this->poModel->createProductDescription($workorder);
                    unset($workorder->product);
                    unset($workorder->model);
                    $json_data = json_encode((object) $completedWorkorders);
                } 
            }


        print_r ($json_data);
        exit;
        } 

        public function setSerials($wkoID, $serials) {
            
        }

    }