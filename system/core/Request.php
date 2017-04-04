<?php

class Request
{

    public $url;
    public $path;

    public $domain;
    public $method;

    public $ssl = false;

    public $scheme;
    public $hostname;

    public $ajax;

    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->scheme = $_SERVER["REQUEST_SCHEME"];
        $this->hostname = $_SERVER["HTTP_HOST"];
        $this->domain = $this->hostname;

        // get url path from root of request
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $this->url = $this->scheme . "://{$this->hostname}{$this->path}";

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
