<?php


   
    function throwErr($errNo, $errStr) {
        echo '<div style="display:flex; text-align:center;padding-left:4rem;">';
        echo '<h1>Error ['.$errNo.'] :<BR>'.$errStr.'.</h1>';
        echo '</div>';
        die();
    }

    //set_error_handler("throwErr");
    