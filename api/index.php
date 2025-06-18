<?php 


class Api extends Controller {
    

    private $woModel;
    function __construct(){
        $this->woModel->model('Workorders');
        // echo '<SCRIPT>console.log("hello")</SCRIPT>';
     //   die('i have been accessed');
     send_response('ello!',710);
        
    }


    
}

