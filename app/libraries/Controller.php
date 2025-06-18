<?php

    /**
     * Base Controller
     * 
     * loads the models and views
     * ----------------
     * 
     * @author gcpipe 
     */

     class Controller {
        public function model($model){
            //require model file
            require_once '../app/models/' .$model . '.php';
            //instanciate model
            return new $model();
        }
        //load view
        public function view ($view, $data = []){
            //check for view file
            if(file_exists('../app/views/'. $view .'.php')){
                require_once '../app/views/'. $view. '.php';
            } else {
                //if model file doesn't exist
                die('View does not exist');
            }

        }

     }