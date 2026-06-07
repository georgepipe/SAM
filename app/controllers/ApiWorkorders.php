<?php 

    class ApiWorkorders extends Controller {
        private $woModel;
        private $poModel;
        private $moModel; 
        private $seModel;

        public function __construct() {
            //API VALIDATION GOES HERE
            $this->moModel = $this->model('Model');
            $this->woModel = $this->model('Workorder');
            $this->poModel = $this->model('Product');
            $this->seModel = $this->model('Serial');
        }

        public function paginate($type = '', $page = '') {
            
            if ($type === 'active') {
                $activeWorkorders = $this->woModel->getActiveOrders($page);
                $descriptionService = new ProductDescriptionService($this->moModel, $this->poModel, $this->woModel);
                foreach($activeWorkorders as $workorder) { 
                    $workorder = $descriptionService->createProductDescription($workorder); 
                } 

                $this->jsonResponse($activeWorkorders);

            } elseif ($type === 'completed') {
                $completedWorkorders = $this->woModel->getCompletedOrders($page);
                $descriptionService = new ProductDescriptionService($this->moModel, $this->poModel, $this->woModel);
                foreach($completedWorkorders as $workorder) {
                    $workorder = $descriptionService->createProductDescription($workorder);
                } 
                $this->jsonResponse($completedWorkorders);
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
            $status = trim($input['status'] ?? '');

            $allowedStatuses = [
                'In Progress',
                'On Hold',
                'To Be Built',
                'Upcoming'
            ];

            if(empty($workorderId) || empty($status)) {
                http_response_code(422); //unprocessable entity code
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing workorder ID or Status'
                ]);
                return;
            }
            if(!in_array($status, $allowedStatuses, true)) {
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid workorder status'
                ]);
                return;
            }

            $updated = $this->woModel->updateStatus($workorderId, $status);

            if(!$updated) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Internal database error'
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Status updated successfully hee hee'
            ]);
            return;

        }
        
        public function setSerials() {
            /*  
            ✓ numeric validation - 
            ✓ quantity validation
            ✓ work order still open - 
                ✓ range validation - 
                ✓ duplicate validation - 
                ✓ serials not already used - 
            **/
            header('Content-Type: application/json');
            //GET INPUT
            $input = json_decode(file_get_contents('php://input'), true); 
            $workorderId = $input['workorder_id'] ?? null;
            $serials = $input['numbers'] ?? null;
            $model = $input['model'] ?? null;

            //validate input
            if(!$workorderId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid input, workorder id is missing'
                ]);
                return;
            }
            if(!$serials) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid input: serial(s) missing'
                ]);
                return;
            }

            //GET WORKORDER & MID
            $workorder = $this->woModel->getWorkorderById($workorderId);

            if(!$workorder) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid work order id, workoder does not exist in database.'
                ]);
                return;
            }

            $mid = $this->moModel->getMidFromPid($workorder->pid);

            // $this->seModel->valdateSerials($workorder, $mid, $serials);
            //validate workorder exists

            //Validate quantity
            if ($workorder->quantity !== count($serials)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Quantity of serial provided is '.count($serials).' but workorder requires '.$workorder->quantity
                ]);
                return;
            }

            //Validate WORKORDER IS NOT ALREADY COMPLETED
            if($workorder->wko_status === 'Completed') {
                echo json_encode([
                    'success' => false,
                    'message' => 'This workorder is already completed and cannot have serials added to it'
                ]); 
                return;
            }

            //VALIDATE SERIALS ARE NUMBERS
            $current = null;
            $previous = 0;
            $usedSerials = [];
            $flaggedAvns = [];

            foreach ($serials as $serial) {
                if(!is_numeric($serial)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Serials may only contain numbers'
                    ]);
                    return;
                }

                //VALIDATE SERIALS ARE INCREMENTAL
                $current = $serial;
                if($current <= $previous) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Serials must be provided from smallest to largest'
                    ]); 
                    return;
                } else {
                    $previous = $serial;
                }

                //Check for used serials
                $flaggedSerial = $this->seModel->validateNewSerial($mid, $serial);
                if($flaggedSerial) {
                    //serial is already in use, create a list of these and return at the end of validation checks
                    $flaggedWorkorder = $this->woModel->getWorkorderById($flaggedSerial->work_order_id);
                    // array_push($usedSerials, $serial);
                    $usedSerials[] = $serial;
                    // array_push($flaggedAvns, $flaggedWorkorder->avn);
                    $flaggedAvns[] = $flaggedWorkorder->avn;
                };
            };
            //Validate serials are all unused
            if(count($usedSerials) > 0 ){
                $usedSerials = implode(',', $usedSerials);
                $flaggedAvns = implode(',', $flaggedAvns);

                echo json_encode([
                    'success' => false,
                    'message' => 'The serial(s) '.$usedSerials.' are/is used in AVN '.$flaggedAvns
                ]); 
                return;
            }

            //VALIDATE NO DUPLICATES EXIST
            if(count($serials) !== count(array_unique($serials))) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Serials must all be unquie, duplicates detected'
                ]);
                return;
            };
            
            //PASSED VALIDATION
            //SAVE SERIAL TO WORKORDER
            $serials = implode(',', $serials);
            if(!$this->woModel->setSerials($workorderId,$serials)){
                echo json_encode([
                    'success' => false,
                    'message' => 'Error: Failed to save serials to workorder'
                ]);
                return;
            };
            
            //MARK WORKORDER AS COMPLETE 
            //I Will do this on the client side to keep the alert banner working the same as the other completed workorders that already have serials
            if(!$this->woModel->completeOrder($workorder)) {
                echo json_encode([
                    'success' => 'false',
                    'message' => 'Error: Failed to mark workorder as completed'
                ]); 
            }


            //FINISHED
            flash('post_message', 'Work Order marked as completed!');

            echo json_encode([
                'success' => true,
                'message' => 'Successfully added serial(s) and marked workorder as complete'
            ]);
            return;

        }


        private function jsonResponse($payload, int $statusCode = 200): void
        {
            http_response_code($statusCode);
            header('Content-Type: application/json');
            echo json_encode($payload);
            return;
        }

    }