<?php


class Workorder {
    
    private $db;
    // private $moModel;
    // private $poModel;

        public function __construct() {
            $this->db = new Database;
            // $this->moModel = new Model();
            // $this->poModel = new Product();
        }

        public function getOrders(int $page = 1){
            switch ($page) {
                case 1:
                    $this->db->query('select * from work_orders order by created_at desc limit 10');
                    break;
                default:
                    //load 10 results starting from $page x 10 offset
                    $offset = $page * 10;
                    $this->db->query('SELECT * from work_orders order by created_at desc limit 10 offset :offset');
                    $this->db->bind(':offset',$offset);
                    break;
            }
            // echo '<pre>';
            // print_r($results);
            // die('');
            $results = $this->db->resultSet();
            return $results;
        }

        public function getActiveOrders(int $page = 0){
            switch ($page) {
                case 0:
                    $this->db->query('select * from work_orders where wko_status <> "Completed" order by created_at desc limit 10');
                    break;
                default:
                    $offset = $page * 10;
                    $this->db->query('select * from work_orders where wko_status <> "Completed" order by created_at desc limit 10 offset :offset');
                    $this->db->bind(':offset',$offset);    
            }
            $results = $this->db->resultSet();
            return $results;
        }

        public function getCompletedOrders(int $page = 0){
            switch ($page) {
                case 0:
                    $this->db->query('select * from work_orders where wko_status = "Completed" order by created_at desc limit 10');
                    break;
                default:
                    $offset = $page * 10;
                    $this->db->query('select * from work_orders where wko_status = "Completed" order by created_at desc limit 10 offset :offset');
                    $this->db->bind(':offset',$offset);    
            }
            $results = $this->db->resultSet();
            return $results;
        }


        public function getFinishes(){
            $this->db->query('select * from finishes');
            $results = $this->db->resultSet();
            return $results;
        }

        public function getFinishfromId(int $id){
            $this->db->query('select * from finishes WHERE id = :id');
            $this->db->bind(':id', $id);
            $results = $this->db->Single();
            return $results;
        }

        public function getFidfromName(int $finish,bool $isSH){
           
            if($isSH===true) {
                $this->db->query('select * from finishes WHERE name = :name and type = :type');
                $this->db->bind(':name', $finish);
                $this->db->bind(':type', 'Polyurethane');
                $row = $this->db->Single();
            } else {
                $this->db->query('select * from finishes WHERE name = :name AND type <> :type');
                $this->db->bind(':type', 'Polyurethane');
                $this->db->bind(':name', $finish);
                $row = $this->db->Single();
                
            }
            return $row->id;
        }

        public function getActiveWorkorderCount() {
            $this->db->query('SELECT COUNT(*) AS count from work_orders WHERE wko_status <> "Completed"');
            $results = $this->db->single();
            $results = $results->count;
            return $results;
        }

        public function getCompletedWorkorderCount() {
            $this->db->query('SELECT COUNT(*) AS count from work_orders WHERE wko_status = "Completed"');
            $results = $this->db->single();
            $results = $results->count;
            return $results;
        }


        public function addOrder(object $data) {
            // dumpAndDie($data);
            $data->avn = $data->avn ?? 0;
            $data->wko = $data->wko ?? 0;
            $this->db->query('INSERT INTO work_orders 
                (wko, avn, pid, wheels, quantity, quantity_built, serials, wko_status, wko_delivery, wko_notes) 
                                VALUES
                (:wko, :avn, :pid, :wheels, :quantity, :quantity_built, :serials, :wko_status, :wko_delivery, :wko_notes)');
            $this->db->bind(':pid', $data->pid);
            $this->db->bind(':wko',  $data->wko);
            $this->db->bind(':avn',  $data->avn);
            $this->db->bind(':wheels',  $data->wheels);
            $this->db->bind(':quantity',  $data->quantity);
            $this->db->bind(':quantity_built',  $data->quantity_built);
            $this->db->bind(':serials',  $data->serials);
            $this->db->bind(':wko_status',  $data->wko_status);
            $this->db->bind(':wko_delivery',  $data->wko_delivery);
            $this->db->bind(':wko_notes',  $data->wko_notes);
            // try {return $this->db->execute();} catch (PDOException $e) {
            //     print_r($e);
            // } 
            
            return $this->db->execute() ? true : false;
        }

        public function editOrder(object $data) {
            $this->db->query('UPDATE work_orders SET 
                work_order_id = :work_order_id,
                wko = :wko,
                avn = :avn, 
                pid = :pid, 
                wheels = :wheels,
                quantity = :quantity,
                serials = :serials,
                wko_status = :wko_status,
                wko_delivery = :wko_delivery,
                wko_notes = :wko_notes,
                updated_at = :updated_at
                WHERE work_order_id = :work_order_id');
            // print 'prebind <br>';

            date_default_timezone_set('Europe/London');
        ////bind values
        // print_r($data);
        // die('asdadas');
            $this->db->bind(':work_order_id',  $data->work_order_id);
            $this->db->bind(':wko',  $data->wko);
            $this->db->bind(':avn',  $data->avn);
            $this->db->bind(':pid',  $data->pid);
            $this->db->bind(':wheels',  empty($data->wheels) ? 0 : 1);
            $this->db->bind(':quantity',  $data->quantity);
            $this->db->bind(':serials',  $data->serials);
            $this->db->bind(':wko_status',  $data->wko_status);
            $this->db->bind(':wko_delivery',  $data->wko_delivery);
            $this->db->bind(':wko_notes',  $data->wko_notes);
            $this->db->bind(':updated_at', date('Y-m-d h:i:s', time()));
         //execute

            // try {$this->db->execute();} catch (PDOException $e) {
            //     print_r($e);
            // }

            // die('this will not update!');
            if($this->db->execute()){
                return true;
            } else { 
                return false;
            }
        }

        public function setSerials(int $wkoid,string $serials) { //is this needed or will an api be more suitable?
            //update wko with given serials
            $this->db->query('
                UPDATE work_orders SET 
                serials = :serials
                WHERE work_order_id = :work_order_id
                            ');

            $this->db->bind('work_order_id', $wkoid);
            $this->db->bind('serials', $serials);

            if($this->db->execute()){
                return true;
            } else { 
                return false;
            } 
        }

        public function completeOrder(object $data) {
            $this->db->query('UPDATE work_orders SET 
                work_order_id = :work_order_id,
                avn = :avn, 
                wko = :wko,
                pid = :pid,
                wheels = :wheels,
                quantity = :quantity,
                serials = :serials,
                wko_status = :wko_status,
                wko_delivery = :wko_delivery,
                wko_notes = :wko_notes,
                updated_at = :updated_at,
                completed_at = :completed_at
                WHERE work_order_id = :work_order_id');

            $this->db->bind(':work_order_id',  $data->work_order_id);
            $this->db->bind(':wko',  $data->wko);
            $this->db->bind(':avn',  $data->avn);
            $this->db->bind(':pid',  $data->pid);
            $this->db->bind(':wheels',  $data->wheels);
            $this->db->bind(':quantity',  $data->quantity);
            $this->db->bind(':serials',  $data->serials);
            $this->db->bind(':wko_status',  "Completed");
            $this->db->bind(':wko_delivery',  $data->wko_delivery);
            $this->db->bind(':wko_notes',  $data->wko_notes);
            $this->db->bind(':updated_at', date('Y-m-d h:i:s', time()));
            $this->db->bind(':completed_at', date('Y-m-d h:i:s', time()));

            if($this->db->execute()){
                return true;
            } else { 
                return false;
            }
        }

        public function getWorkorderByWko(string $wko) {
            $this->db->query('SELECT * FROM work_orders WHERE wko = :wko');
            $this->db->bind(':wko', $wko);
            $row = $this->db->single();
            return $row;
        }

        public function getWorkorderByAvn(int $avn) {
            $this->db->query('SELECT * FROM work_orders WHERE avn = :avn');
            $this->db->bind(':avn', $avn);
            $row = $this->db->single();
            return $row;
        }

        public function getWorkorderById(int $id) {
            $this->db->query('SELECT * FROM work_orders WHERE work_order_id = :work_order_id');
            $this->db->bind(':work_order_id', $id);
            $this->db->execute();
            $row = $this->db->single();
            return $row;
        }

        public function deleteOrder(int $id) {
            $this->db->query('DELETE FROM work_orders WHERE work_order_id = :work_order_id');
         //bind values
            $this->db->bind(':work_order_id', $id);
         //execute
            if($this->db->execute()){
                flash('post_message','Order Removed');
                redirect('workorders');
            } else { 
                die('something went wrong');
                }
        }

        public function getAvaliableWorkOrdersForTransport() {
            $this->db->query('SELECT * FROM work_orders WHERE tnid is null and wko_status = "Completed" LIMIT 25');
            try {
                $results = $this->db->resultSet();} catch (PDOException $e) {
                    print_r($e);
                }
            return $results; 
        }

        public function getWorkOrdersFromTransportNote(int $tnid) {
            //do things
            $this->db->query('SELECT * FROM work_orders WHERE tnid = :tnid');
            $this->db->bind(':tnid', $tnid); 
            $results = $this->db->resultSet();
            return $results;
        }

        public function getWorkOrdersWithTransportNotes() {
            $this->db->query('select * from work_orders where not tnid = "null"');
            $results = $this->db->resultSet();
            return $results;
        }    

        public function getPidFromOptions(object $data) {
            switch(empty($data->waveguide_finish_id)) {
                case 0: //has waveguide
                    switch(empty($data->grille_finish_id)) {
                        case 0: //has grille
                            $this->db->query('SELECT pid FROM finished_product WHERE 
                            cab_model_id = :cab_model_id AND 
                            cab_finish_id = :cab_finish_id AND 
                            grille_finish_id = :grille_finish_id AND 
                            waveguide = :waveguide');
                            $this->db->bind(':cab_model_id', $data->cab_model_id);
                            $this->db->bind(':cab_finish_id', $data->cab_finish_id);
                            $this->db->bind(':grille_finish_id', $data->grille_finish_id);
                            $this->db->bind(':waveguide_finish_id', $data->waveguide_finish_id);
                            break;
                        case 1: //no grille
                            switch(empty($data->cab_finish_id)) {
                                case 0:
                                    $this->db->query('SELECT pid FROM finished_product WHERE 
                                    cab_model_id = :cab_model_id AND 
                                    cab_finish_id = :cab_finish_id AND 
                                    grille_finish_id is null AND 
                                    waveguide = :waveguide_finish_id');
                                    $this->db->bind(':cab_model_id', $data->cab_model_id);
                                    $this->db->bind(':cab_finish_id', $data->cab_finish_id);
                                    $this->db->bind(':waveguide_finish_id', $data->waveguide_finish_id);
                                    break;
                                case 1:
                                    $this->db->query('SELECT pid FROM finished_product WHERE 
                                    cab_model_id = :cab_model_id AND 
                                    cab_finish_id is null AND 
                                    grille_finish_id is null AND 
                                    waveguide = :waveguide_finish_id');
                                    $this->db->bind(':cab_model_id', $data->cab_model_id);
                                    $this->db->bind(':waveguide_finish_id', $data->waveguide_finish_id);
                            }
                            break;
                    }
                    break;
                case 1: //no waveguide
                    switch(empty($data->grille_finish_id)) {
                        case 0: //has grille
                            $this->db->query('SELECT * FROM finished_product WHERE 
                                cab_model_id = :cab_model_id and 
                                cab_finish_id = :cab_finish_id AND 
                                grille_finish_id = :grille_finish_id AND 
                                waveguide is NULL');
                            $this->db->bind(':cab_model_id', $data->cab_model_id);
                            $this->db->bind(':cab_finish_id', $data->cab_finish_id);
                            $this->db->bind(':grille_finish_id', $data->grille_finish_id);

                        break;
                        case 1: //no grille
                            $this->db->query('SELECT pid FROM finished_product WHERE 
                                cab_model_id = :cab_model_id AND 
                                cab_finish_id = :cab_finish_id AND 
                                grille_finish_id is null AND 
                                waveguide is null');
                            $this->db->bind(':cab_model_id', $data->cab_model_id);
                            $this->db->bind(':cab_finish_id', $data->cab_finish_id);
                        break;

                    }
                    break;
            };
            
            $results = $this->db->single();
            if(isset($results->pid)){
                return $results->pid; 
            } else {
                return 0;
            }
            
        }

        public function updateWorkorderTnid(object $workorder): bool {
            $this->db->query('UPDATE work_orders SET tnid = :tnid WHERE work_order_id = :work_order_id');
            $this->db->bind(':tnid', $workorder->tnid);
            $this->db->bind(':work_order_id', $workorder->work_order_id);

            try {$this->db->execute();} catch (PDOException $e) {
                print_r($e);
            }
            return $this->db->execute() ? true : false;
        }

        public function removeTnid(object $workorder): bool {
            $this->db->query('UPDATE work_orders SET tnid = :tnid WHERE work_order_id = :work_order_id');
            $this->db->bind(':tnid', NULL);
            $this->db->bind(':work_order_id', $workorder->work_order_id);

            try {$this->db->execute();} catch (PDOException $e) {
                print_r($e);
            }
            return $this->db->execute() ? true : false;
        }        

        public function updateStatus(int $workorderId, string $workorderStatus): bool {
            $this->db->query('UPDATE work_orders SET wko_status = :wko_status WHERE work_order_id = :work_order_id');
            $this->db->bind(':wko_status', $workorderStatus);
            $this->db->bind(':work_order_id', $workorderId);

            return $this->db->execute() ? true : false;
        }

    public function setProductDescription(object $workorder): object{
            // $acWkoCount = $this->getActiveWorkorderCount();
            // $coWkoCount = $this->getCompletedWorkorderCount();
            // $workorder->product = $this->poModel->getProductFromPid($workorder->pid);

            // dumpAndDie($workorder);
            // $workorder->model = $this->moModel->getModelFromMid($workorder->product->cab_model_id);

            
            // $workorder->cab_finish = $this->getFinishfromId($workorder->product->cab_finish_id ?? 0);
            // if(!empty($workorder->grille_finish)) {$workorder->grille_finish = $this->getFinishfromId($workorder->product->grille_finish_id ?? 0);}
            // if (!empty($workorder->waveguide_finish)){$workorder->waveguide_finish = $this->getFinishfromId($workorder->product->waveguide ?? 0);}
            // $workorder->pdesc = $this->poModel->createProductDescription($workorder);
            // unset($workorder->product);
            // unset($workorder->model);
        }

    }
