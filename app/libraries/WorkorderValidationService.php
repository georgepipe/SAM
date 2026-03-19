<?php
//REPORTS ERRORS ONLY, DOES NOT CHANGE DATA

    class WorkorderValidationService {

        private $woModel;
        private $seModel;

        public function __construct($woModel, $seModel) {
            $this->woModel = $woModel;
            $this->seModel = $seModel;
        }

        public function validate($workorderPayload): array {
            // print_r(workorderPayload);
            // die('stop');
        }

        public function workorderRefExistsInDB($workorderRef): bool {

        }

        public function workorderAvnExistsInDb($workorderAvn): bool {

        }

        public function getFinishIdFromString($finish): int {

        }

        public function getModelIdFromString($model): int {

        }

        public function workorderHasModel($workorderModel, $workorderWaveguide): bool {

        }

        public function getWaveguideFinishIdFromString($waveguide): int {

        }

        public function checkGrilleRequirement($model, $grille) {

        }


    }