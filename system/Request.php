<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */
class Request
{

    public $url;
    public $path;
    public $uri;

    public $domain;
    public $method;

    public $ssl = false;

    public $scheme;
    public $hostname;

    public $http;
    public $https;
    public $ajax;

    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->scheme =  $_SERVER["REQUEST_SCHEME"];
        $this->hostname =  $_SERVER["HTTP_HOST"];
        $this->domain = $this->hostname;
        $this->uri =  "/" . trim($_SERVER["REQUEST_URI"], "/") . "/";

        $this->url = $this->scheme . "://" . $this->hostname . $this->uri;

        // get url path from root of request
        $this->path = isset($_GET['_uri']) ? "/" . trim($_GET['_uri'], "/") . "/" : "/";
        unset($_GET['_uri']); // unset URI so it doesn't get included in $input->get array and can't be accessed later


        $this->ssl = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
        $this->ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');


        // setup GET & POST variables
        $this->get = new stdClass();
        foreach ($_GET as $key => $value) {
            $this->get->$key = $value;
        }

        $this->post = new stdClass();
        foreach ($_POST as $key => $value) {
            $this->post->$key = $value;
        }



    }

}

