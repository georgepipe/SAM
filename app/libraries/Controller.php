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
            return new $model();
        }
        //load view
        public function view ($view, $data = []){
            if(file_exists('../app/views/'. $view .'.php')){
                require_once '../app/views/'. $view. '.php';
            } else {
                throwErr(404, "View does not exist!");
            }

        }

     }