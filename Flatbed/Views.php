<?php

namespace Flatbed;
class Views extends Objects
{

    protected $rootFolder = "views";
    protected $singularName = "View";

    public function get( string $name): ?Object
    {

        $file = SITE_PATH . "views" . DIRECTORY_SEPARATOR . "{$name}.php";

        // fallback to checking system folder for file
        if (!file_exists($file)) {
            $file = SYSTEM_PATH . "views" . DIRECTORY_SEPARATOR . "{$name}.php";
        }

        if(!file_exists($file)) return null;

        $view = new View($file);

        return $view;
        
    }

    public function render( string $name)
    {

        $view = $this->get($name);
        return $view->render($page);
        
    }




}