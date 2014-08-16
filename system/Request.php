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

    public $subdomain;
    public $domain;
    public $method;

    public $ssl = false;

    public $scheme;
    public $hostname;
    public $username;
    public $password;
    public $fragment;
    public $query;

    public $http;
    public $https;
    public $ajax;

    public function __construct()
    {

        $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $this->url = $url;

        $this->method = $_SERVER["REQUEST_METHOD"];

        // get url path from root of request
        $this->path = isset($_GET['_uri']) ? "/" . $_GET['_uri'] : "/";
        unset($_GET['_uri']); // unset URI so it doesn't get included in $input->get array and can't be accessed later

        // http vars
        $this->http = new stdClass();
        $this->http->host = $_SERVER["HTTP_HOST"];


        $this->query = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";


        // setup GET & POST variables
        $this->get = new stdClass();
        foreach ($_GET as $key => $value) {
            $this->get->$key = $value;
        }

        $this->post = new stdClass();
        foreach ($_POST as $key => $value) {
            $this->post->$key = $value;
        }

        // HTTPS and AJAX
        $this->https = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
        $this->ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');

    }

}

