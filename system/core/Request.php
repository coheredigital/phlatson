<?php

class Request
{

    public $httpUrl;

    // array store path requested and all possible parent paths
    public $urls = [];

    public $matchedUrl;

    // TODO : consider seperating request::segments from response:segments
    public $segments = [];
    public $segmentsString = [];

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
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // $this->segments = $this->getSegments($url);
        $this->urls = $this->getUrls($url);


        $this->httpUrl = $this->scheme . "://{$this->hostname}{$this->url}";

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

    public function get(string $name)
    {
        switch ($name) {
            case 'url':
                return $this->urls[0];
            default:
                return null;
        }
    }

    public function segment(int $position) {
        $index = $position - 1;
        return $this->segments[$index];
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