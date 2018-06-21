<?php
namespace Phlatson;
class Request
{

    public $httpUrl;
    public $path;

    // array store path requested and all possible parent paths
    public $urls = [];

    // TODO : consider seperating request::segments from response:segments
    public $segments = [];

    public $domain;
    public $method;

    public $ssl = false;

    public $scheme;
    public $hostname;

    public $ajax;
    public $ip = null;
    public $userAgent ;


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
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->urls = $this->getUrls($this->path);
        $this->httpUrl = $this->scheme . "://{$this->hostname}{$this->url}";

        $this->ssl = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
        // find the client ip
        $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        $this->ip = $this->ip === null && isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;

        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

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


    /**
     * get the segment portion at the request position, counting from left to right
     * @param  int    $position
     * @return string           the URL portion at the posisiton set
     */
    public function segment(int $position) : ?string
    {
        $index = $position - 1;
        return $this->segments[$index];
    }

    public function get(string $name)
    {
        switch ($name) {
            case 'url':
                return $this->urls[0];
            case 'segment':
                return implode("/", $this->segments) . "/";
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