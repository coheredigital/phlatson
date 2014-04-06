<?php

class InputUrl
{

    private $url;

    function __construct($url)
    {

        $this->url = isset($_GET['_url']) ? $_GET['_url'] : "";
        unset($_GET['_url']);

        $this->_setup();
    }

    protected function _setup()
    {


        $this->query = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";


    }


}