<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Request {

    public $url;
    public $method;

    public $query;

    public $http;

    public function __construct(){

        $this->method = $_SERVER["REQUEST_METHOD"];

        // get url path from root of request
        $this->url = isset($_GET['_uri']) ? "/" . $_GET['_uri'] : "/";
        unset( $_GET['_uri'] ); // unset URI so it doesn't get included in $input->get array and can't be accessed later

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


    }

}

