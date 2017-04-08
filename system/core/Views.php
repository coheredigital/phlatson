<?php


class Views extends Objects
{

    protected $rootFolder = "users";
    protected $singularName = "User";



    public function get( string $name)
    {

    	$file = SITE_PATH . "views" . DIRECTORY_SEPARATOR . "{$name}.php";
        if (file_exists($file)) return $file;

    	$file = SYSTEM_PATH . "views" . DIRECTORY_SEPARATOR . "{$name}.php";
        if (file_exists($file)) return $file;
        
        return null;
        
    }


}