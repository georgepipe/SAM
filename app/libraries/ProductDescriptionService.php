<?php

// .  ProductDescriptionService.php
 //   ProductDescriptionService
class ProductDescriptionService{

    private $moModel;
    private $woModel;
    private $poModel;

    public function __construct($moModel, $poModel, $woModel) {
        $this->moModel = $moModel;
        $this->woModel = $woModel;
        $this->poModel = $poModel;

    }

    private function initDescArr() {
        $descArr = (object) [
            'name' => '',
            'scolour' => '',
            'driveUnits' => '',
            'amping' => '',
            'xover' => '',
            'connectors' => '',
            'colour' => '',
            'waveguide' => '',
            'grille' => '',
            'fixings' => '',
            'features' => '',
            'weatherResistant' => ''
        ];

        return $descArr;
    }

    public function createProductDescription(object $workorder) {
        
        $descArr = $this->initDescArr();

        $this->hydrateWorkorder($workorder);
        // $descArr = (object) [];
        //speaker model name
        $descArr->name = $workorder->model->name ?? 'No name';
        //Cabinet colour abbreviation
        if($workorder->cab_finish){
            $descArr->scolour = $this->getColourAbreviation($workorder->cab_finish);
        };
        //Drive units
        $descArr->driveUnits = $workorder->model->drive_units ?? null;
        //Amping
        $descArr->amping = $workorder->model->amping ?? null;
        //X-over
        $descArr->xover = $workorder->model->x_over ? "(".$workorder->model->x_over.")" : null;
        //Connectors
        $descArr->connectors = $this->getConnectors($workorder->product) ?? null; 
        //Cabinet colour
        if(!is_null($workorder->cab_finish)) {
            $descArr->colour = $this->getColour($workorder->cab_finish->name, $workorder->cab_finish->type, $workorder->cab_finish->ral_code);
        } else {
            // $descArr->scolour = '';
        };
        //waveguide colour
        $descArr->waveguide = $workorder->waveguide->name ?? null;
        if ($descArr->waveguide) {
            $descArr->waveguide .= ' waveguide';
        }
        //grille colour
        $descArr->grille = $this->getGrille($workorder) ?? null;
        //Fixings
        $descArr->fixings = $workorder->product->fixings." fixings" ?? null;
        //Features
        $descArr->features = $workorder->model->features ?? null;
        //WR?
        $descArr->weatherResistant = $workorder->cab_finish->type === 'Weather Resistant' ? 'Weather resistant' : null;
        //construct description
        $desc = $this->constructDescription($descArr);
        // //set description
        $workorder->pdesc = $desc;
        // //remove objects that are no longer needed in workorder before returning it
        $this->unsetObjects($workorder);
        return $workorder;
    }

    public function createShortProductDescription(object $workorder) {
    
        $this->hydrateWorkorder($workorder);
        $descArr = (object) [];
        //speaker model name
        $descArr->name = $workorder->model->name ?? 'No name';
        //Cabinet colour abbreviation
        if($workorder->cab_finish){
            $descArr->scolour = $this->getColourAbreviation($workorder->cab_finish);
        };
        //waveguide colour
        $descArr->waveguide = $workorder->waveguide->name ?? null;
        if ($descArr->waveguide) {
            $descArr->waveguide .= ' waveguide';
        }
        //grille colour
        $descArr->grille = $this->getGrille($workorder);
        //construct description
        $desc = $this->constructDescription($descArr);
        //set description
        $workorder->pdesc = $desc;
        //remove objects that are no longer needed in workorder
        $this->unsetObjects($workorder);
        return $workorder;

        
    }

    private function hydrateWorkorder(object $workorder):object {
        $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
        $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
        $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->cab_finish_id ?? 0) ?? null;
        $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id ?? 0);
        $workorder->waveguide = $this->woModel->getFinishfromId($workorder->product->waveguide ?? 0); 
        return $workorder;
    }

    private function getColour($colourName, $type, $ral) {
        $colour = '';
        // if($type === 'Weather Resistant') $colour = 'Polyurea';
        $colour .= $colourName;
        if($ral !=='RAL 9005' && $ral !== 'RAL 9003' && $ral !== null) $colour .= ' ('.$ral.')';
        if($colourName) return $colour.' cabinet';
    }

    private function getColourAbreviation(object $cabFinish): string{
            $abreiviatedColour = match($cabFinish->type) {
                'Standard' => $cabFinish->name[0],
                'Weather Resistant' => 'PU-'.$cabFinish->name[9],
                'Custom Weather Resistant' => 'PU-X',
                'Custom' => 'X',
                'Wood' => 'UN',
                default => null,
            };
            return $abreiviatedColour;
    }

    private function getGrille(object $workorder) {
        $grille = null;
        if($workorder->grille_finish){
            $grille = match($workorder->product->fixings) {
                'BZP Steel','Black p/Steel' => $workorder->grille_finish->name." M/Steel grille",
                'S/Steel','Black S/Steel' => $workorder->grille_finish->name." S/Steel grille",
                default => null,
            };
        };
        if($workorder->product->cab_model_id === 19) {
            $grille = 'Brushed S/Steel';
        }

        if(!$grille && $workorder->cab_finish) {
            $grille = 'no grille';
        }
        return $grille;
    }

    private function getConnectors(object $product) {
        $connectorMap = [
            'PHX' => 'Phoenix connectors',
            'NL4' => 'NL4 connectors',
            'NL8' => 'NL8 connectors',
            'NL4 & PHX' => 'NL4 & Pheonix connectors'
        ];
        return $connectorMap[$product->connectors] ?? null;
    }

    private function constructDescription($descArr) {
        $desc = $descArr->name;
        if($descArr->scolour) {
            $desc .= " ($descArr->scolour)";
        } else {
            $desc .= " (".strtoupper($descArr->waveguide[0]).")";
        };
        $parts = array_filter([
            $descArr->driveUnits,
            $descArr->amping,
            $descArr->xover,
            $descArr->connectors,
            $descArr->colour,
            $descArr->waveguide,
            $descArr->grille,
            $descArr->fixings,
            $descArr->features,
            $descArr->weatherResistant
        ]);
        if($parts) {
            $desc .= ' '.implode(', ',$parts);
        }
        return $desc;
    }

    private function unsetObjects(object $workorder):object {
        unset($workorder->product);
        unset($workorder->model);
        unset($workorder->cab_finish);
        unset($workorder->grille_finish);
        unset($workorder->waveguide);
        return $workorder;
    }
}


