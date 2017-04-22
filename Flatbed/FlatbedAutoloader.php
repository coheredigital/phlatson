<?php
// spl_autoload_register('flatbedAutoloader');

namespace Flatbed;

class FlatbedAutoloader {


    public function __construct()
    {
        spl_autoload_register([$this ,'load']);
        
    }

    public function load($class)
    {
        

        // normalize directory separators
        $class = str_replace("\\", DIRECTORY_SEPARATOR,$class);
        $parts = explode(DIRECTORY_SEPARATOR, $class);

        // first check if in root system
        $file = ROOT_PATH . $class . ".php";
        
        // look for extension if no Core class exists
        if (!is_file($file)) {
            $file = $this->getExtension($class);
        }

        // if(!is_file($file)) {
        //     throw new FlatbedException\FlatbedException("Flatbed could not load the class '{$class}'!");
        // }

        require_once $file;  

    }



    protected function getExtension ($class) 
    {

        // break the $classname into parts
        $classParts = explode(DIRECTORY_SEPARATOR, $class);

        // get only the last part as the name of the extension
        $name = \array_pop($classParts);
        // break the class name into parts and get the first as a potential folder for the extension
        // example: MarkupPagetree retrun Markup
        $folders = preg_split("/(?=[A-Z])/", $name, 0, PREG_SPLIT_NO_EMPTY );
        $folder = $folders[0];

        // array to store potential locations of extensions
        $files = [];


        $files[] = SITE_PATH . "extensions" . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . ".php";
        $files[] = SITE_PATH . "extensions" . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $name . ".php";
        
        $files[] = SYSTEM_PATH . "extensions" . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . ".php";
        $files[] = SYSTEM_PATH . "extensions" . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $name . ".php";


        // loop through locations and return the first found file
        foreach ($files as $file) {
            if (is_file($file)) return $file;
        }


    }

}