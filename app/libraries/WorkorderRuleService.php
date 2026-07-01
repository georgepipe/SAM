<?php   
//FILLS IN DEFAULT DATA TO MEET PRODUCT RULESETS AND SETS DEFAULTS IS APPLICABLE
//CONVERTS STRING FORM ENTRIES INTO THEIR RESPECTIVE DB IDs
//CONVERT LONG STRINGS TO ABBREVIATED STRINGS WHERE APPLICABLE TO MATCH DB FORMAT

    class WorkorderRuleService {

        private $woModel;

        public function __construct($woModel, $moModel){
            $this->woModel = $woModel;
            $this->moModel = $moModel;
        }

        public function apply(object $data): object {
            $this->applyDefaults($data);
            $this->getIdsFromStrings($data);
            return $data;
        }

        private function applyDefaults(object $data): object {
            $this->applyGrilleRules($data);
            $this->applyConnectorRules($data);
            $this->applySerialRules($data);
            $this->applyWheelRules($data);
            $this->applyFixingRules($data);

            return $data;
        }

        private function getIdsFromStrings(object $data): object {
            $this->getCabFinishIdFromString($data);
            $this->getModelIdFromString($data);
            $this->getGrilleFinishIdFromString($data);
            $this->getWaveguideFinishIdFromString($data);

            return $data;
        }

        private function applyGrilleRules(object $data): void {
            $defaultGrilleColour = $this->defaultGrilleFinish($data->form->cab_model_id);
            if(empty($data->form->grille_finish_id) && $defaultGrilleColour != null) {
                $data->form->grille_finish_id = $defaultGrilleColour;
            }
        }

        private function applyConnectorRules(object $data): void {

            $defaultConnectors = $this->defaultConnectors($data->form->cab_model_id); //find default connectors for a given cab model
            if(empty($data->form->connectors) && $defaultConnectors != null) {
                $data->form->connectors = $defaultConnectors;
            } else {
                if($data->form->connectors === 'NL4 & Phoenix') {
                    $data->form->connectors = 'NL4 & PHX';
                }
            }
        }

        private function applySerialRules(object $data): void {
            if(empty($data->form->serials) | $data->form->serials == 'TBC') {
                $data->form->serials = 'To Be Confirmed';
            }
            //5 digits per number!
            // $data->form->serials = $this->padSerials($data->form->serials);
        }

        private function padSerials(string $input): string {
            return preg_replace_callback('/\d+/', function($match) {
                return str_pad($match[0], 5, '0', STR_PAD_LEFT);
            }, $input);
        }

        private function applyWheelRules(object $data):void {
            if(!$data->form->wheels) $data->form->wheels = 0;
        }

        private function applyFixingRules(object $data) {
            //replace apostrophes with slashes
            //capitalise fixings names p'steel => p/Steel
            $data->form->fixings = preg_replace('/\'/','/',$data->form->fixings);
            $data->form->fixings = preg_replace('/s/','S',$data->form->fixings);
            $data->form->fixings = preg_replace('/b/i','B',$data->form->fixings);
            $data->form->fixings = preg_replace('/bzp/i','BZP',$data->form->fixings);
        }

        private function getCabFinishIdFromString(object $data): void {
            if(!is_numeric($data->form->cab_finish_id) && !empty($data->form->cab_finish_id)) {
                $sh = preg_match('/(SH)/',$data->form->cab_model_id);
                $data->form->cab_finish_id = $this->woModel->getFidFromName($data->form->cab_finish_id, !empty($sh));
            }
        }

        private function getModelIdFromString(object $data): void {
            if (!is_numeric($data->form->cab_model_id)) {
                $data->form->cab_model_id = $this->moModel->getMidFromName($data->form->cab_model_id);
            }
        }

        private function getGrilleFinishIdFromString(object $data): void {
            //  get grille colour from form grille input
            $decoded = html_entity_decode($data->form->grille_finish_id);
            $regex = "/\s*.'steel/i";
            $data->form->grille_finish_id = preg_replace($regex,'',$decoded);

            if (!is_numeric($data->form->grille_finish_id) && !empty($data->form->grille_finish_id)) {
                $data->form->grille_finish_id = $this->woModel->getFidFromName($data->form->grille_finish_id, 0); 
            }
        }

        private function getWaveguideFinishIdFromString(object $data): void {
            if(!is_numeric($data->form->waveguide_finish_id) && !empty($data->form->waveguide_finish_id)) {
                $sh = preg_match('/(SH)/',$data->form->cab_model_id);
                $data->form->waveguide_finish_id = $this->woModel->getFidFromName($data->form->waveguide_finish_id, isset($sh) ? true : false);
            }
        }

        public function requiresGrilleFinish($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderRuleConfig::REQUIRES_GRILLE_FINISH, TRUE);
        }

        public function defaultGrilleFinish($cabModelID): ? int {
            return WorkorderRuleConfig::DEFAULT_GRILLE_FINISH[(string)$cabModelID] ?? null;
        }

        public function requiresConnectorSelection($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderRuleConfig::REQUIRE_CONNECTOR_SELECTION, TRUE);
        }

        public function defaultConnectors($cabModelID) {
            foreach(WorkorderRuleConfig::DEFAULT_CONNECTORS as $connector => $values) {
                if(in_array($cabModelID, $values, TRUE)) {
                    return $connector;
                }
            }
            // return WorkorderRuleConfig::DEFAULT_CONNECTORS[(string)$cabModelID] ?? 'NL4';
        }

        public function requiresWaveguideFinish($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderRuleConfig::REQUIRE_WAVEGUIDE_FINISH, TRUE);
        }

        public function requiresCabinetFinish($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderRuleConfig::REQUIRE_CABINET_FINISH, TRUE);
        }




    }







