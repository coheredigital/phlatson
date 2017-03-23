<?php


class Views extends Objects
{



    public function __construct()
    {
        // store paths and urls 
        $this->path = Filter::path( ROOT_PATH . "site/views/" );
        $this->systemPath = Filter::path( ROOT_PATH . "system/views/" );

        $this->url = Filter::url( ROOT_URL . "site/views/");
        $this->systemUrl = Filter::url( ROOT_URL . "site/views/");
    }



    public function get($name)
    {

    	$file = $this->api('config')->paths->root . "site/views/{$name}.php";
        if (file_exists($file)) return $file;

    	$file = $this->api('config')->paths->root . "system/views/{$name}.php";
        if (file_exists($file)) return $file;

        return null;
        
    }


}