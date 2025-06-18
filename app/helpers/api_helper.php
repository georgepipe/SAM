<?php


    /**
     * get the api method used: POST GET etc
     * @return string
     */
    function get_method (){
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get data object from API data
     * @return array The data object
     */
    function get_request_data () {
        return array_merge(empty($_POST) ? array() : $_POST, (array) json_decode(file_get_contents('php://input'), true), $_GET);
    }

    /**
     * return HTML response header
     */
    function send_response ($response, $code = 200) {
        http_response_code($code);
        die(json_encode($response));
    }

    function is_not_ajax() {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest';
    }
