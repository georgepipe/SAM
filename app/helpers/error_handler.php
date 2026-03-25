<?php

    function dumpAndDie(... $vars) {
        echo '<pre>';
        // echo 'VAR DUMP<BR>';
        // foreach($vars as $var) {
        //     var_dump($var);
        // }
        echo 'print_r<BR>';
        foreach($vars as $var) {
            print_r($var);
        }
        echo '</pre>';
        die();
    }
   
    function throwErr($errNo, $errStr) {
        echo '<div style="display:flex; text-align:center;padding-left:4rem;">';
        echo '<h1>Error ['.$errNo.'] :<BR>'.$errStr.'.</h1>';
        echo '</div>';
        die();
    }

    //set_error_handler("throwErr");
    