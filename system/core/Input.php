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

        $this->url = isset($_GET['_url']) ? $_GET['_url'] : "/";
        unset($_GET['_url']);

        $this->query = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";

        $this->request = explode("/", trim($this->url, "/"));

        // setup GET & POST variables
        $get = new stdClass();
        foreach ($_GET as $key => $value) {
            if ($key == "_url") {
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