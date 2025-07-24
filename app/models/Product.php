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
            // echo '<PRE>';
            // print_r($workorder);
            // die('<BR>fuckin wat?!');
        //name, colour abbreviation, series, drive units, amping, connectors, cabinet colour, grille, fixings, features
            $name = empty($workorder->model->name) ? 'No name' : $workorder->model->name;

            $scolour = match($workorder->cab_finish->type) {
                'Standard' => $workorder->cab_finish->name[0],
                'Weather Resistant' => 'PU-'.$workorder->cab_finish->name[9],
                'Custom Weather Resistant' => 'PU-X',
                'Custom' => 'X',
                'Wood' => 'UN',
                default => '',
            };

            $xover = empty($workorder->model->x_over) ? '' : '('.$workorder->model->x_over.') ';
            $connectors = match($workorder->model->mid) {
                39,44,46,48,50,53,55,57 => 'NL4',
                default => $workorder->product->connectors,
            };
            if(!is_null($workorder->grille_finish)) {
                if(!is_null($workorder->grille_finish->type)) {
                    $grille = match($workorder->grille_finish->type) {
                        'Standard' => "m'Steel ".$workorder->grille_finish->name." grille, ",
                        'Weather Resitant' => "s'Steel ".$workorder->grille_finish->name." grille, ",
                        'Custom Weather Resistant' => "s'Steel ".$workorder->grille_finish->name." grille, ",
                        default => '',
                    };
                }
                $grille = $grille ?? 'no grille, ';
            };
        //final string construction
            $pdesc = $name.' '.$scolour.' ('.$workorder->model->type.') '.$workorder->model->drive_units.': '.$workorder->model->amping.' '.
                $xover.$connectors.' connectors, '.$workorder->cab_finish->name.' cabinet, '.$grille.$workorder->product->fixings.' fixings, '.$workorder->model->features;
            return $pdesc;
        }
       
        public function addPidFromOptions($data) {
            $this->db->query('INSERT INTO finished_product 
                (model_id, finish_id, grille_finish_id, connectors, waveguide, fixings)
                     VALUES
                (:model_id, :finish_id, :grille_finish_id, :connectors, :waveguide, :fixings)');
            $this->db->bind(':model_id', $data->cab_model_id);
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