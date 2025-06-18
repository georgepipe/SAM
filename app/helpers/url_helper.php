<?php

    //simple page redirect
    function redirect($page) {
        echo 'redirect!';
        header('Location: ' . URLROOT .$page);
    }

    