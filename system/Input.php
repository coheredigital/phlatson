<?php

class Input
{

    public $url;
    public $query;
    public $request;

    function __construct()
    {

        $this->url = isset($_GET['_uri']) ? $_GET['_uri'] : "/";
        unset( $_GET['_uri'] ); // unset URI so it doesn't get included in $input->get array and can't be accessed later


        $this->query = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";

        $this->request = explode("/", trim( $this->url , "/"));

        // setup GET & POST variables
        $get = new stdClass();
        foreach ($_GET as $key => $value) {
            $get->$key = $value;
        }
        if (count((array)$get)) {
            $this->get = $get;
        }

        $post = new stdClass();
        foreach ($_POST as $key => $value) {
            $post->$key = $value;
        }
        if (count((array)$post)) {
            $this->post = $post;
        }

    }



}