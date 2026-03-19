<?php   

    class WorkorderRuleService {

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

        public function requiresWaveguideColour($cabModelID): bool {
            return in_array((string) $cabModelID, WorkorderRuleConfig::REQUIRE_WAVEGUIDE_FINISH);
        }




    }







