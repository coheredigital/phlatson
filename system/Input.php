<?php

class Input
{

    public $url;
    public $query;
    public $request;

    function __construct()
    {
        $this->setup();
    }


    protected function setup()
    {

        $this->url = isset($_GET['uri']) ? $_GET['uri'] : "/";
        unset($_GET['uri']);

        $this->query = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";

        $this->request = explode("/", trim($this->url, "/"));

        // setup GET & POST variables
        $get = new stdClass();
        foreach ($_GET as $key => $value) {
            if ($key == "uri") {
                continue;
            } // skip XPages specific request
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