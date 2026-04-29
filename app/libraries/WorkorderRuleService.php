<?php   
//FILLS IN DEFAULT DATA TO MEET PRODUCT RULESETS AND SETS DEFAULTS IS APPLICABLE
//CONVERTS STRING FORM ENTRIES INTO THEIR RESPECTIVE DB IDs

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
            $defaultConnectors = $this->defaultConnectors($data->form->cab_model_id);
            if(empty($data->form->connectors) && $defaultConnectors != null) {
                $data->form->connectors = $defaultConnectors;
            }
        }

        private function applySerialRules(object $data): void {
            if(empty($data->form->serials) | $data->form->serials == 'TBC') {
                $data->form->serials = 'To Be Confirmed';
            }
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
            //  get grille colour from pdf grille input
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

        public function defaultConnectors($cabModelID): string {
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







