<?php

class Response
{

    protected $status = 200;
    protected $headers;
    protected $cached = false;

    public $body = null;

    // prevent further changes
    protected $locked = false;

    public function __construct()
    {

    }

    public function header($header)
    {
        $this->headers[] = $header;
    }

    /**
     * redirects page using PHP header
     * @param  string or Page object  $url   redirect to url from root or to page
     * @param  boolean $permanent is redirect permanent?
     */
    public function redirect($location, $permanent = true)
    {

        $url = $location instanceof Page ? $location->url : $location;

        $this->code($code);
        $this->header('Location', $url);
        $this->lock();


        if ($permanent) {
            $this->header("HTTP/1.1 301 Moved Permanently");
        }
        header("Location: $url");
        return $this;
    }


    public function lock()
    {
        $this->locked = true;
    }

    public function render()
    {

        echo $this->body;

    }

}

