<?php

/**
 * Core
 * ----------------------------------
 * Creates URL & loads core controller
 * URL FORMAT - /controller/methods/params
 * 
 * @author GCPipe
 */
 class Core {
    //default pages
    protected $currentController = 'pages';
    protected $currentMethod = 'index';
    protected $params = [];
    /**
     * Gets the url, checks wether the controller specifed by url[0] and method specified
     * by url[1] exist before loading the controller and running the method with the params
     * specified by url[3]+
     */
    public function __construct() {
        $url = $this->getUrl();
                if(file_exists('../app/controllers/'. ucwords($url[0]).'.php')){
                    $this->currentController = ucwords($url[0]);
                    unset($url[0]);
                } else {
                    if(!empty($url[0])) {
                        if($url[0]==='advice_notes'){
                            //404!
                            header("Location: ".URLROOT."pages/_404");
                            die('this will redirect to 404 page!');
                        } else {
                            print_r(" !file not found in app/controllers ");
                            print_r($url);
                        }
                    }
                }
                require_once '../app/controllers/'. $this->currentController . '.php';
                $this->currentController = new $this->currentController;
                
                // if ($currentMethod === 'index') {die('its index!');} else {$currentMethod = 'index';}
                if (isset($url[1])){
                    if(method_exists($this->currentController, $url[1])){
                        $this->currentMethod = $url[1];
                        unset($url[1]);
                    } else {print_r("!method not found ");echo '</br>';}
                }
                $this->params = $url ? array_values($url) : [];
                call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }


    /**
     * getURL
     * gets the url from browser, trims whitespace, 
     * santises and breaks into array $url
     * 
     * @return $url[]
     */
    public function getUrl() {
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/',$url);
            //print_r($url); //debug point
            return $url;
        }

    }



 }
