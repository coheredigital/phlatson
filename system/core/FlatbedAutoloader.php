<?php
// spl_autoload_register('flatbedAutoloader');


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
        if ($parts[0] == "Flatbed") {
            array_shift($parts);
        }
        if (count($parts)) {
            $class = implode($parts);
        }
        

        // first check if in root system
        $file = CORE_PATH . "$class.php";

        // then for folder with that name
        if (!is_file($file)) {
            $file = CORE_PATH . $class . DIRECTORY_SEPARATOR . $class . ".php";
        }
        
        //  r($file);
        // //  r($parts);

        // then look to extensions
        if (!is_file($file)) {


            $extensionsSitePath = SITE_PATH . "extensions" . DIRECTORY_SEPARATOR . $class . DIRECTORY_SEPARATOR;
            $extensionsSystemPath = SYSTEM_PATH . "extensions" . DIRECTORY_SEPARATOR . $class . DIRECTORY_SEPARATOR;

            $extensionSite = $extensionsSitePath . $class . ".php";
            $extensionSystem = $extensionsSystemPath . $class . ".php";

            if (!is_file($extensionSite) && !is_file($extensionSystem)) {
                throw new FlatbedException("Extension {$class} does not exist / cannot be found!");
            }
            
            if (is_file($extensionSystem)) $file = $extensionSystem;
            // a site extension can replace a System core extension
            if (is_file($extensionSite)) $file = $extensionSite;


        }

        if(!is_file($file)) {
            throw new FlatbedException("Flatbed could not load the class '{$class}'!");
        }

        require_once $file;  

    }



}