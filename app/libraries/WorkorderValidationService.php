<?php
//REPORTS ERRORS ONLY, DOES NOT CHANGE DATA

    class WorkorderValidationService {

        private $woModel;
        private $seModel;
        private $ruleService;

        public function __construct($woModel, $seModel, workorderRuleService $ruleService) {
            $this->woModel = $woModel;
            $this->seModel = $seModel;
            $this->ruleService = $ruleService;
        }

        public function validate(object $data): object {
            $this->validateWKO($data);
            $this->validateAVN($data);
            $this->validateModelSelection($data);
            $this->validateCabinetFinishSelection($data);
            $this->validateGrilleSelection($data);
            $this->validateConnectorSelection($data);
            $this->validateQuantity($data);
            $this->validateSerials($data);
            $this->validateWaveguideSelection($data);
            $this->setPID($data);

            return $data;
        }

        public function hasErrors($errors): bool {
            foreach ($errors as $error) {
                if(!empty($error)) {
                    return TRUE;
                }
            }
            return FALSE;
        }

        private function validateWKO(object $data): void {
            if($this->woModel->getWorkorderByWko($data->form->wko)) {
                if (!$data->form->wko == '') {
                    $data->errors->err_wko = 'A work order already exists with this WKO';
                }
            }
        }

        private function validateAVN(object $data): void {
            if($this->woModel->getWorkorderByAvn($data->form->avn) ) {
                if(!$data->form->avn == '') {
                    $data->errors->err_avn = 'A work order already exists with this AVN';
                }
            } 
        }

        private function validateModelSelection(object $data): void {
            if(empty($data->form->cab_model_id)) {
                $data->errors->err_cab_model_id = 'Please select the cabinet model';
            }
        }

        private function validateCabinetFinishSelection(object $data): void {
            if($this->ruleService->requiresCabinetFinish($data->form->cab_model_id) && empty($data->form->cab_finish_id)) {
                $data->errors->err_cab_finish_id = 'Please select the cabinet finish';
            }
        }

        private function validateGrilleSelection(object $data): void {
            if(empty($data->form->grille_finish_id)) { 
                if($this->ruleService->requiresGrilleFinish($data->form->cab_model_id)) {
                    $data->errors->err_grille_colour = 'Please select a grille colour';
                }
            }
            if(str_contains($data->form->grille_finish_id,'Steel')) {
                $explodedGrille = explode(' ',$data->form->grille_finish_id);
                $data->form->grille_finish_id = $explodedGrille[0]; 
            }
        }

        private function validateConnectorSelection(object $data): void {
            if(empty($data->form->connectors)) {
                if($this->ruleService->requiresConnectorSelection($data->form->cab_model_id)) {
                    $data->errors->err_connectors = "Please select a connector type";
                }
            }
        }

        private function validateQuantity(object $data): void {
            if(empty($data->form->quantity)) {
                $data->errors->err_quantity = 'Please enter a quantity';
            }
        }

        private function validateSerials(object $data): void {
             //validate serial string
             if(preg_match('/([A-z])/',$data->form->serials) && $data->form->serials != 'To Be Confirmed') {
                $data->errors->err_serials = "Serials cannot contain characters";
            } else {
                //check serials for duplicates
                $duplicates = $this->seModel->addSerials($data->form->work_order_id, $data->form->cab_model_id, $data->form->serials, 1);
                if(isset($duplicates[0]->sid)) {
                    //there are duplicate serials
                    $data->errors->err_serials = 'Duplicate serials detected: ';
                    foreach($duplicates as $duplicate) {
                        $workorder = $this->woModel->getWorkorderById($duplicate->work_order_id);
                        $data->errors->err_serials = $data->errors->err_serials.'AVN: '.$workorder->avn.' Serial ->'.$duplicate->serial_num.' :';
                    }
                } 
                //check number of serials against quantity
                if($data->form->serials != 'To Be Confirmed') { 
                    $sCount = $this->seModel->countSerials($data->form->serials);
                    if($data->form->quantity != $sCount && !empty($data->form->serials)) {
                        if($sCount > $data->form->quantity) {
                        $data->errors->err_quantity = 'Quantity is '.$data->form->quantity.' but '.$sCount.' serials have been specified.';
                        } else if ($sCount == 1) {
                            $data->errors->err_quantity = 'Quantity is '.$data->form->quantity.' but only '.$sCount.' serial has been specified.'; 
                        } else {
                            $data->errors->err_quantity = 'Quantity is '.$data->form->quantity.' but only '.$sCount.' serials have been specified.';  
                        }
                    }
                }
            }
        }

        private function validateWaveguideSelection(object $data): void {
            if(empty($data->form->waveguide_finish_id)) { 
                if($this->ruleService->requiresWaveguideFinish($data->form->cab_model_id)) {
                    $data->errors->err_waveguide_finish_id = 'Please select a waveguide colour';
                } else {$data->errors->err_waveguide_finish_id = '';}
            };
        }

        private function setPID(object $data): void{
            $pid = $this->woModel->getPidFromOptions($data->form); 
            $data->form->pid = $pid;
        }
    }