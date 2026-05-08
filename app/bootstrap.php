<?php 

    //load config
    require_once 'config/Config.php';
    require_once 'config/DbConfig.php';
    
    //load helpers
    require_once 'helpers/url_helper.php';
    require_once 'helpers/session_helper.php';
    require_once 'helpers/api_helper.php';
    require_once 'helpers/error_handler.php';
    require_once 'DTOs/PdfData.php';
    // require_once 'helpers/pdf_parser.php';

    //load vendors (pdfparser)
    require '../vendor/autoload.php';
    // dumpAndDie(file_exists('../vendor/autoload.php'));
    // $parser = new parser;
    // dumpAndDie($parser);

    //autoload core libraries
    spl_autoload_register(function($className){

        // require_once 'libraries/'. $className .'.php';
        $path = APPROOT . '/libraries/' . $className . '.php';

        if(file_exists($path)){
            require_once $path;
        }
    });



