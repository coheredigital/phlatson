<?php

class Request
{

    public $httpUrl;

    // array store path requested and all possible parent paths
    public $urls = [];

    public $matchedUrl;

    // TODO : consider seperating request::segments from response:segments
    public $segments = [];
    public $segmentsString = '';

    public $domain;
    public $method;

    public $ssl = false;

    public $scheme;
    public $hostname;

    public $ajax;
    public $ip;


    public $cookies;
    public $get;
    public $post;


    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->scheme = $_SERVER["REQUEST_SCHEME"];
        $this->hostname = $_SERVER["HTTP_HOST"];
        $this->domain = $this->hostname;

        // get url path from root of request
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->urls = $this->getUrls($url);
        $this->httpUrl = $this->scheme . "://{$this->hostname}{$this->url}";

        $this->ssl = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
        $this->ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');

        $this->ip = $_SERVER['REMOTE_ADDR'];

        // set params
        $this->get = count($_COOKIES) ? $this->objectify($_COOKIES) : null;
        $this->get = count($_GET) ? $this->objectify($_GET) : null;
        $this->post = count($_POST) ? $this->objectify($_POST) : null;


    }


    protected function objectify(array $array) {
        return json_decode( json_encode( $array ) );
    }


    protected function getUrls($url) {
        $segments = explode("/", trim($url, '/')); 
        $urls = [];
        while (count($segments)) {
            $urls[] = "/" . implode("/", $segments) . "/";
            array_pop($segments);
        }
        return $urls;
    }

    protected function getSegments($url) {
        $segments = explode("/", trim($url, '/'));        
        return $segments;
    }

    public function setMatch(int $key)
    {
        $this->matchedUrl = $key;

        $matchedUrl = $this->urls[$key];
        $this->segmentsString = "/" . str_replace($matchedUrl, '', $this->url);

        $this->segments = $this->getSegments($this->segmentsString);

    }



    public function segment(int $position) {
        $index = $position - 1;
        return $this->segments[$index];
    }

    public function get(string $name)
    {
        switch ($name) {
            case 'url':
                return $this->urls[0];
            default:
                return null;
        }
    }


    /**
     * give property access to all get() variables
     * @param  string $name
     * @return mixed
     */
    final public function __get( string $name)
    {
        return $this->get($name);
    }

}