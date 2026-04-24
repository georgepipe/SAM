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


    public function createProductDescription(object $workorder) {
            $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
            $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
            $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->cab_finish_id ?? 0);
            $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id ?? 0);
            $workorder->waveguide_finish_id = $this->woModel->getFinishfromId($workorder->product->waveguide ?? 0);
        //name, colour abbreviation, series, drive units, amping, connectors, cabinet colour, grille, fixings, features
            $name = $workorder->model->name ?? 'No name';

            // $sh = preg_match('/(SH)/',$workorder->model->name);
            if(!is_bool($workorder->cab_finish)){
                $scolour = match($workorder->cab_finish->type) {
                    'Standard' => $workorder->cab_finish->name[0],
                    'Weather Resistant' => 'PU-'.$workorder->cab_finish->name[9],
                    'Custom Weather Resistant' => 'PU-X',
                    'Custom' => 'X',
                    'Wood' => 'UN',
                    default => '',
                };
                $cabFinishName = $workorder->cab_finish->name.' cabinet, ';
            } else {
                $cabFinishName = '';
            };
            $waveguide = $workorder->waveguide->name ?? '';
            $scolour = empty($scolour) ? $waveguide : $scolour;

            $xover = empty($workorder->model->x_over) ? '' : '('.$workorder->model->x_over.') ';
            $connectors = match($workorder->model->mid) {
                39,44,46,48,50,53,55,57 => 'NL4',
                default => $workorder->product->connectors,
            };

            $grille = '';
            if(!empty($workorder->grille_finish)){
                if(!is_bool($workorder->grille_finish)) {
                    if(!is_bool($workorder->grille_finish->type)) {
                        $grille = match($workorder->grille_finish->type) {
                            'Standard' => "M/Steel ".$workorder->grille_finish->name." grille, ",
                            'Weather Resitant' => "S/Steel ".$workorder->grille_finish->name." grille, ",
                            'Custom Weather Resistant' => "S/Steel ".$workorder->grille_finish->name." grille, ",
                            default => '',
                        };
                    };
                };
            };
            if($grille == '') {$grille = 'no grille, ';}
        //final string construction
            $workorder->pdesc = $name.' '.$scolour.' ('.$workorder->model->type.') '.$workorder->model->drive_units.': '.$workorder->model->amping.' '.
                $xover.$connectors.' connectors, '.$cabFinishName.$grille.$workorder->product->fixings.' fixings, '.$workorder->model->features;    
            
            unset($workorder->product);
            unset($workorder->model);
            unset($workorder->cab_finish);
            unset($workorder->grille_finish);
            return $workorder;
    }

    public function createShortProductDescription(object $workorder) {
        $workorder->product = $this->poModel->getProductFromPid($workorder->pid);
        $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);
        $workorder->cab_finish = $this->woModel->getFinishfromId($workorder->product->cab_finish_id ?? 0);
        $workorder->grille_finish = $this->woModel->getFinishfromId($workorder->product->grille_finish_id ?? 0);
        $workorder->waveguide_finish_id = $this->woModel->getFinishfromId($workorder->product->waveguide ?? 0); 
    
        $name = $workorder->model->name ?? 'No name';
        // $sh = preg_match('/(SH)/',$workorder->model->name);
        if(!is_bool($workorder->cab_finish)){
            $scolour = match($workorder->cab_finish->type) {
                'Standard' => $workorder->cab_finish->name[0],
                'Weather Resistant' => 'PU-'.$workorder->cab_finish->name[9],
                'Custom Weather Resistant' => 'PU-X',
                'Custom' => 'X',
                'Wood' => 'UN',
                default => '',
            };
            $cabFinishName = $workorder->cab_finish->name.' cabinet, ';
        } else {
            $cabFinishName = '';
        };
        $waveguide = $workorder->waveguide->name ?? '';
        $scolour = empty($scolour) ? $waveguide : $scolour; 
        $grille = '';
        if(!empty($workorder->grille_finish)){
            if(!is_bool($workorder->grille_finish)) {
                if(!is_bool($workorder->grille_finish->type)) {
                    $grille = match($workorder->grille_finish->type) {
                        'Standard' => $workorder->grille_finish->name."m/steel grille, ",
                        'Weather Resitant' => $workorder->grille_finish->name."s/steel grille, ",
                        'Custom Weather Resistant' => $workorder->grille_finish->name."s/steel grille, ",
                        'Metal' => $workorder->grille_finish->name."s/steel grille, ",
                        default => '',
                    };
                };
            };
        };
        if($grille == '') {$grille = 'no grille, ';}
        $scolour ? '' : $scolour = $waveguide_finish_id;
        $workorder->pdesc = $name.' '.$scolour.' '.$cabFinishName.$grille;    
        
        unset($workorder->product);
        unset($workorder->model);
        unset($workorder->cab_finish);
        unset($workorder->grille_finish);
        return $workorder;

        
    }

}


