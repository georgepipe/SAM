<?php
    class Model {

        private $db;

        public function __construct() {
             $this->db = new Database;

        }

        //parse model ID from url, scan db for product id and load relavant information
        public function getModels(){
            $this->db->query('select * from models');
            $results = $this->db->resultSet();
            return $results;
        }

        public function getModelNames(){
            $this->db->query('SELECT mid, name FROM models');
            try {$results = $this->db->resultSet();} catch (PDOException $e) {
                PRINT_R($e);
            };
            
            return $results;
        }

        public function getModelsForTNs(object $workorders) {
            // $this->db->query('select * from models');
            // $results = $this->db->resultSet();  
            // $models=[];  
            foreach($workorders as $workorder) {
                 array_push($models,$workorder->cab_model_id);
            };
            return $models;
        }

        public function getModelFromMid(int $mid) {
            $this->db->query('SELECT * FROM models WHERE mid = :mid');
            $this->db->bind(':mid' , $mid);
            $model = $this->db->single();
            return $model;
        }           

        public function getMidFromName(string $name) {
            $this->db->query('SELECT mid FROM models WHERE name = :name');
            $this->db->bind(':name', $name);
            $mid = $this->db->single();
            return $mid->mid;
        }

        public function getWeightFromMid(int $mid) {
            $this->db->query('SELECT weight FROM models WHERE mid = :mid');
            $this->db->bind(':mid', $mid);
            $weight = $this->db->single();
            return $weight->weight;
        }
    }

