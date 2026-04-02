<?php 

    class ApiWorkorders extends Controller {
        private $woModel;
        private $poModel;
        private $moModel; 

        public function __construct() {
            //API VALIDATION GOES HERE
            $this->moModel = $this->model('Model');
            $this->woModel = $this->model('Workorder');
            $this->poModel = $this->model('Product');
        }

        public function paginate($type = '', $page = '') {
            
            if ($type === 'active') {
                $activeWorkorders = $this->woModel->getActiveOrders($page);
                foreach($activeWorkorders as $workorder) {
                    $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
                    $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
                    $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->finish_id);
                    $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id);
                    $workorder->waveguide_finish_id = $this->woModel->getFinishfromId($workorder->product->waveguide_finish_id);
                    $workorder->pdesc = $this->poModel->createProductDescription($workorder);

                    unset($workorder->product);
                    unset($workorder->model);

                    $this->jsonResponse($activeWorkorders);
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

                    $this->jsonResponse($completedWorkorders);
                } 
            }
        } 

        public function updateStatus(){
            header('Content-Type: application/json');
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405); //method error code
                echo json_encode([
                    'success' => false,
                    'message' => 'Method not allowed'
                ]);
                return;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            $workorderId = $input['workorder_id'] ?? null;
            $status = trim($input['status']) ?? '';

            $allowedStatuses = [
                'In Progress',
                'On Hold',
                'To Be Built',
                'Upcoming'
            ];

            if(empty($workorderId || empty($status))) {
                http_response_code(422); //unprocessable entity code
                echo json_encode([
                    'sucess' => false,
                    'message' => 'Missing workorder ID or Status'
                ]);
                return;
            }
            if(!in_array($status, $allowedStatuses, true)) {
                http_response_code(422);
                echo json_encode([
                    'sucess' => false,
                    'message' => 'Invalid workorder status'
                ]);
                return;
            }

            $updated = $this->woModel->updateStatus($workorderId, $status);

            if(!$updated) {
                http_response_code(500);
                echo json_encode([
                    'sucess' => false,
                    'message' => 'Internal database error'
                ]);
                return;
            }

            echo json_encode([
                'sucess' => true,
                'message' => 'Status updated sucessfully hee hee'
            ]);
            return;

        }

        public function setSerials($wkoID, $serials) {
            //To be constructed
        }


        private function jsonResponse($payload, int $statusCode = 200): void
        {
            http_response_code($statusCode);
            header('Content-Type: application/json');
            echo json_encode($payload);
            exit;
        }

    }