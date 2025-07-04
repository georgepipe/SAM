<?php

    //DB parameters
    define('DB_HOST','localhost');
    define('DB_USER','root');
    define('DB_PASS','gcp94');
    define('DB_NAME','SAMDB');
    //app route
    define('APPROOT', dirname(dirname(__FILE__)));
    //url root
    define('URLROOT', 'http://localhost/SAM/');
    //site name
    define('SITENAME', 'F1 Speaker Assembly Manager');
    //temp folder
    define('TEMPDIR' , dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/temp/' );
    //advice notes folder
    define('AVNDIR', dirname(dirname(dirname(__FILE__))).'/public/advice_notes/');
