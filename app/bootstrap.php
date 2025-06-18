<?php 

    //load config
    require_once 'config/Config.php';
    
    //load helpers
    require_once 'helpers/url_helper.php';
    require_once 'helpers/session_helper.php';
    require_once 'helpers/api_helper.php';
    require_once 'helpers/error_handler.php';
    // require_once 'helpers/pdf_parser.php';

    //autoload core libraries
    spl_autoload_register(function($className){
        require_once 'libraries/'. $className .'.php';
    });


