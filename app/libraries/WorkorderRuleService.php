<?php   

    class WorkorderRuleService {

        public function requiresGrilleFinish($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderProductRules::REQUIRES_GRILLE_COLOUR, TRUE);
        }

        public function defaultGrilleFinish($cabModelID): ? int{
            return WorkorderProductRules::DEFAULT_GRILLE_FINISH((string)$cabModelID ?? null);
        }

        public function requiresConnectorSelection($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderProductRules::REQUIRE_CONNECTOR_SELECTION, TRUE);
        }

        public function defaultConnectors($cabModelID): string {
            return WorkorderProductRules::DEFAULT_CONNECTORS((string)$cabModelID ?? 'NL4');
        }

        public function requiresWaveguideColour($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderProductRules::REQUIRE_WAVEGUIDE_FINISH);
        }




    }







