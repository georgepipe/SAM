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
   
    function throwErr(int $errNo,string $errStr,object $vars = null) {
        echo '<div style="display:flex; justify-content:center;text-align:center;padding-left:4rem;padding-top: 200px;">';
        echo '<h1>Error ['.$errNo.'] :<BR>'.$errStr.'</h1>';
        echo '</div>';
        if($vars) {
            echo '<h1 style="display:flex; justify-content:center;">Related Variable(s)</h1>';
            echo '<pre style="display:flex; justify-content:center;">';
            print_r($vars);
            echo '</pre>';
        };
        die();
    }

    //set_error_handler("throwErr");
    