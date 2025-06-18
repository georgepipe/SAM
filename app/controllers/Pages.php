<?php

class Pages  extends Controller {
    public function __construct() {
    }
    public function index() {
        $data = [
            'title' => 'Speaker Assembly Manager aka SAM'
        ];


        $this->view('/pages/index', $data);
    }

    public function about() {
        $data = [
             'title' => 'Welcome to About'
        ];

        $this->view('/pages/about', $data);
        }
}