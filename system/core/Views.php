<?php


class Views extends Flatbed
{

    protected $rootFolder = "users";
    protected $singularName = "User";

    public function get( string $name)
    {

    	$file = SITE_PATH . "views" . DIRECTORY_SEPARATOR . "{$name}.php";

        // fallback to checking system folder for file
        if (!file_exists($file)) {
            $file = SYSTEM_PATH . "views" . DIRECTORY_SEPARATOR . "{$name}.php";
        }

        $view = new View($file);

        return $view;
        
    }


}