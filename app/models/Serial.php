<?php


class Serial {
        private $db;

        public function __construct() {
             $this->db = new Database;

        }

        public function addSerials($work_order_id, $mid, $serials, $vOnly = false) {
            // echo 'wko: '.$work_order_id.'mid: '.$mid.'ser: '.$serials;
            if (!$vOnly && $serials === "To Be Confirmed") {throwErr(1024,'Function [addSerials] failed: No serials provided.');}
            //work out how many serials there are, make a list of them all
            // echo '<pre>';
            if(empty($serials) | $serials === '' | $serials === 'TBC') {
                return false;
            }
            $serial = '';
            $duplicate = false;
            $duplicates = [];
            $serialsArr = [];
            $tempArr = [];
            $serialRangeArr = explode('/',$serials);
            $serialRangeArr = $serialRangeArr ?? $serials;
            foreach($serialRangeArr as $serialRange) {
                $s = explode('-',$serialRange);
                $first = trim($s[0]);
                $last = trim($s[1]);
                $last = empty($last) ? $first : $last;
                $serialArr = range($first, $last, 1);
                // print_r($serialArr);
                foreach($serialArr as $serial) {
                    $serial = $this->validateSerial($mid, $serial);
                    if(isset($serial->serial_num)) {
                        $duplicate = true;
                        array_push($duplicates,$serial);
                    };
                }
                $tempArr = [];
            }
            //so at this point we have created an array of all the individual serial nums in the wko 
            //and created an array of duplicates and set the duplicates flag to true if any were found
            if($duplicates) {
                //return error with list of duplicated serials and their wkos or false flag
                return $duplicates ?? false;
            } else {
                
                if(!$vOnly) { //if we're not just checking for duplicates then add the serials to the database
                    // echo '<PRE>';
                    // print_r($serialArr);
                    foreach($serialArr as $serial) {
                        $this->db->query('INSERT INTO serials (work_order_id, mid, serial_num) VALUES (:work_order_id, :mid, :serial_num)');
                        $this->db->bind(':work_order_id', $work_order_id);
                        // echo 'wko: '.$work_order_id.'mid: '.$mid.'Serial num: '.$serial;

                        $this->db->bind(':mid', $mid);
                        $this->db->bind(':serial_num', $serial);
                        $this->db->execute();
                    }
                }
            }
            return true;
        }


        public function validateSerial($mid, $serial) {
            //returns false if no serial is found
            $this->db->query('SELECT * FROM serials WHERE mid = :mid AND serial_num = :serial_num');
            $this->db->bind(':mid', $mid);
            $this->db->bind(':serial_num', $serial);
            $row = $this->db->single();
            return empty($row) ? false : $row ;
            
        }


        public function expandSerials($serials) {
            $serial = '';
            $serialsArr = [];
            $tempArr = [];
            $serialRangeArr = explode('/',$serials);
            foreach($serialRangeArr as $serialRange) {
                $s = explode('-',$serialRange);
                // echo '<br> Serial: ';
                // print_r($s);
                $first = (int)trim($s[0]);
                $last = (int)trim($s[1]);
                $last = (int)empty($last) ? (int)$first : (int)$last;
                $i = $first;
                // echo '<BR>first:'.$first;
                // echo '<BR>last:'.$last;
                for ($i===$first; $i < $last + 1; $i++) {
                    array_push($tempArr,$i);
                    array_push($serialsArr,$i);
                }
                // print_r($tempArr);
                $tempArr = [];
            }
            //$count = count($serialsArr);
            return $serialsArr;
        }

        public function contractSerials($serialsArr) {
            $sLength = count($serialsArr); 
            //$lastSerial = null;
            $i = 0;
            for($i===0; $i < $sLength; $i++) {
                //itterate through serialsArr
                switch (TRUE) {
                    case ($i === 0): //first num
                        $contractedSerials = $serialsArr[$i];
                        break;
                    case ($i === $sLength-1): //last num
                        if($serialsArr[$i]===$serialsArr[$i-1]+1) {
                            $contractedSerials = $contractedSerials.' - '.$serialsArr[$i];
                        } else {
                        $contractedSerials = $contractedSerials.' - '.$serialsArr[$i-1].'/'.$serialsArr[$i];
                        }
                        break;
                    case ($serialsArr[$i] === $serialsArr[$i-1]+1): //sequential number
                        break;
                    case ($serialsArr[$i] != $serialsArr[$i-1]+1): //non-sequential number
                        $contractedSerials = $contractedSerials.' - '.$serialsArr[$i-1].'/'.$serialsArr[$i];
                        break;
                    default:
                        break;
                }
            }
            return $contractedSerials;
        }

        public function countSerials ($serials) {
            $serialsArr = $this->expandSerials($serials);
            return count($serialsArr);
        }
}