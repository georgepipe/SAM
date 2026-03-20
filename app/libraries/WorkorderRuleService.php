<?php   
//FILLS IN DEFAULT DATA TO MEET PRODUCT RULESETS AND SETS DEFAULTS IS APPLICABLE
    class WorkorderRuleService {

        public function applyDefaults(object $data): object {
            $this->applyGrilleRules($data);
            $this->applyConnectorRules($data);
            $this->applySerialRules($data);

            return $data;
        }

        public function applyGrilleRules(object $data): void {
            $defaultGrilleColour = $this->defaultGrilleFinish($data->form->cab_model_id);
            if(empty($data->form->grille_finish_id) && $defaultGrilleColour != null) {
                $data->form->grille_finish_id = $defaultGrilleColour;
            }
        }

        public function applyConnectorRules(object $data): void {
            $defaultConnectors = $this->defaultConnectors($data->form->cab_model_id);
            if(empty($data->form->connectors) && $defaultConnectors != null) {
                $data->form->connectors = $defaultConnectors;
            }
        }

        public function applySerialRules(object $data): void {
            //set 'TO BE CONFIRMED' if blank
            if(empty($data->form->serials)) {
                $data->form->serials = 'To Be Confirmed';
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
            return WorkorderRuleConfig::DEFAULT_CONNECTORS[(string)$cabModelID] ?? 'NL4';
        }

        public function requiresWaveguideFinish($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderRuleConfig::REQUIRE_WAVEGUIDE_FINISH, TRUE);
        }

        public function requiresCabinetFinish($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderRuleConfig::REQUIRE_CABINET_FINISH, TRUE);
        }


    }







