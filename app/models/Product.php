<?php 

    //do stuff
    class Product {
        private $db;

        public function __construct() {
            $this->db = new Database;

        }



        public function getProductFromPid($pid) {
            //do the roar
            $this->db->query('SELECT * FROM finished_product WHERE pid = :pid');
            $this->db->bind(':pid', $pid);
            $product = $this->db->single();
            return $product;
        }


        public function createProductDescription($workorder) {
        //name, colour abbreviation, series, drive units, amping, connectors, cabinet colour, grille, fixings, features
            $name = empty($workorder->model->name) ? 'No name' : $workorder->model->name;

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
                // die($cabFinishName);
            } else {
                $cabFinishName = '';
            };
            // echo '<pre>';
            // print_r($workorder);
            // die('asdsad');
            $scolour = empty($scolour) ? $workorder->waveguide->name[0] : $scolour;

            $xover = empty($workorder->model->x_over) ? '' : '('.$workorder->model->x_over.') ';
            $connectors = match($workorder->model->mid) {
                39,44,46,48,50,53,55,57 => 'NL4',
                default => $workorder->product->connectors,
            };

            $grille = '';
            if(!is_bool($workorder->grille_finish)) {
                if(!is_bool($workorder->grille_finish->type)) {
                    $grille = match($workorder->grille_finish->type) {
                        'Standard' => "M/Steel ".$workorder->grille_finish->name." grille, ",
                        'Weather Resitant' => "S/Steel ".$workorder->grille_finish->name." grille, ",
                        'Custom Weather Resistant' => "S/Steel ".$workorder->grille_finish->name." grille, ",
                        default => '',
                    };
                }
            };
            if($grille == '') {$grille = 'no grille, ';}
        //final string construction
            $pdesc = $name.' '.$scolour.' ('.$workorder->model->type.') '.$workorder->model->drive_units.': '.$workorder->model->amping.' '.
                $xover.$connectors.' connectors, '.$cabFinishName.$grille.$workorder->product->fixings.' fixings, '.$workorder->model->features;
            return $pdesc;
        }
       
        public function addPidFromOptions($data) {
            $this->db->query('INSERT INTO finished_product 
                (cab_model_id, finish_id, grille_finish_id, connectors, waveguide, fixings)
                     VALUES
                (:cab_model_id, :finish_id, :grille_finish_id, :connectors, :waveguide, :fixings)');
            $this->db->bind(':cab_model_id', $data->cab_model_id);
            $this->db->bind(':finish_id', $data->cab_finish_id);
            if(empty($data->grille_finish_id)) {$data->grille_finish_id = NULL;}
            $this->db->bind(':grille_finish_id', $data->grille_finish_id);
            $this->db->bind(':connectors', $data->connectors);
            if(empty($data->waveguide_finish_id)) {$data->waveguide_finish_id = NULL;}
            $this->db->bind(':waveguide', $data->waveguide_finish_id);
            $this->db->bind(':fixings', $data->fixings);
            
            try {return $row = $this->db->execute() ? TRUE : FALSE;} catch (PDOException $e) {print_r($e);}
            //return $this->db->execute() ? true : false;
    }
    }