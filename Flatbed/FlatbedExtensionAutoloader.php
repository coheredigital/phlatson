<?php
// spl_autoload_register('flatbedAutoloader');

namespace Flatbed;

class FlatbedExtensionAutoloader {


    public function __construct()
    {
        \var_dump("YES");
        spl_autoload_register([
          'FlatbedExtensionAutoloader',
          'load'
        ]);
    }
    protected function load ($class)
    {
        \var_dump("YES");
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

        // check site extensions
        // $files[] = SITE_PATH . "extensions" . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . ".php";
        // $files[] = SITE_PATH . "extensions" . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR  . $name . DIRECTORY_SEPARATOR. $name . ".php";

        r($folder);

        // then system
        $files[] = SYSTEM_PATH . "extensions" . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . ".php";
        $files[] = SYSTEM_PATH . "extensions" . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR  . $name . DIRECTORY_SEPARATOR . $name . ".php";


        // loop through locations and return the first found file
        foreach ($files as $file) {
            if (is_file($file)) return $file;
        }


    }

}
