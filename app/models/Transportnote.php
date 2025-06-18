<?php 
    class Transportnote {
        private $db; 
        public function __construct() {
            $this->db = new Database;

        }

        public function getTransportNotes(){
            $this->db->query(
                'select * from transport_notes order by created_at desc');
            $results = $this->db->resultSet();
            return $results;
        }

        public function getActiveTransportNotes(){
            $this->db->query('SELECT * FROM transport_notes where completed = FALSE order by created_at desc');
            $results = $this->db->resultSet();
            return $results;
        }

        public function getCompletedTransportNotes(){
            $this->db->query('SELECT * from transport_notes where completed = TRUE order by created_at desc');
            $results = $this->db->resultSet();
            return $results;
        }

        public function getTransportNoteFromId($tnid) {
            //do things
            $this->db->query('SELECT * FROM transport_notes WHERE tnid = :tnid');
            $this->db->bind(':tnid', $tnid);
            $results = $this->db->single();
            return $results;
        }

        public function addTransportNote($data) {
            $destination = ($data->transport === 'Collection') ? 'Storage' : 'Funktion-One';
            $this->db->query('INSERT INTO transport_notes (transport, destination, completed) 
                                                VALUES (:transport, :destination, :completed)');
            $this->db->bind(':transport', $data['transport']);
            $this->db->bind(':destination', $destination);
            $this->db->bind(':completed', 0);

            return $this->db->execute();
        }

        public function deleteTnote($tnid) {
            $this->db->query('DELETE FROM transport_notes WHERE tnid = :tnid');
         //bind values
            $this->db->bind(':tnid', $tnid);
         //execute
            if($this->db->execute()){
                flash('post_message','Transport Note Removed');
                redirect('transport');
            } else { 
                die('something went wrong');
            }
        }

        public function completeTnote($tnid) {
            $now = date('y-m-d h:i:s');
            //2024-12-09 09:00:3
            $this->db->query('UPDATE transport_notes SET completed = TRUE, completed_at = :completed_at WHERE tnid = :tnid');
            $this->db->bind(':tnid', $tnid);
            $this->db->bind(':completed_at', $now);
            if($this->db->execute()){
                flash('post_message','Transport Note Removed');
                redirect('transport');
            } else { 
                die('something went wrong');
            }
        }
    }