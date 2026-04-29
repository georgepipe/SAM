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

        public function getMidfromPid(int $pid):int {
            $this->db->query('SELECT cab_model_id from finished_product WHERE pid = :pid');
            $this->db->bind(':pid', $pid);
            $mid = $this->db->single();
            return $mid->cab_model_id;
        }
       
        public function addPidFromOptions($data) {
            // dumpAndDie('inside addPidFromOptions',$data);
            $this->db->query('INSERT INTO finished_product 
                (cab_model_id, cab_finish_id, grille_finish_id, connectors, waveguide, fixings)
                     VALUES
                (:cab_model_id, :cab_finish_id, :grille_finish_id, :connectors, :waveguide, :fixings)');
            $this->db->bind(':cab_model_id', $data->cab_model_id);
            $this->db->bind(':cab_finish_id', $data->cab_finish_id);
            if(empty($data->grille_finish_id)) {$data->grille_finish_id = NULL;}
            $this->db->bind(':grille_finish_id', $data->grille_finish_id);
            $this->db->bind(':connectors', $data->connectors);
            if(empty($data->waveguide_finish_id)) {$data->waveguide_finish_id = NULL;}
            $this->db->bind(':waveguide', $data->waveguide_finish_id);
            $this->db->bind(':fixings', $data->fixings);
            //$this->db->execute();

            $this->db->query('SELECT LAST_INSERT_ID()');
            $pid = $this->db->single();
            $pid = $pid->{'LAST_INSERT_ID()'};
            return $pid;
            // try {return $row = $this->db->execute();} catch (PDOException $e) {echo'<pre>';print_r($e);}
            //return $this->db->execute() ? true : false;
    }
    }