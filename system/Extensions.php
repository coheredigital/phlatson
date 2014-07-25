<?php


class Extensions extends Objects
{

    protected $rootFolder = "extensions";
    protected $singularName = "extension";

    protected $info = [];

    public function __construct(){

        $this->getList(api("config")->paths->extensions, api("config")->paths->extensions );

    }


    /**
     * Handle preload of extension to find extensions that require autoloading /  or route definitions
     */
    protected function preload(){

    }


    protected function getList($root, $path, $depth = 1){

        $iterator = new RecursiveDirectoryIterator( $path, RecursiveDirectoryIterator::SKIP_DOTS );
        $iterator = new RecursiveIteratorIterator( $iterator, RecursiveIteratorIterator::SELF_FIRST );

        $iterator->setMaxDepth($depth);

        foreach ($iterator as $item) {

            $itemPath = normalizePath( $item->getPath() );
            $itemFile = $item->getFileName();

            $filePath = $itemPath . $itemFile;

            if( $itemFile != "data.json" && $itemFile != "info.json" ) continue;

            $className = normalizeDirectory(str_replace($root, "", $itemPath));

            // add root items for pages to allow home selection

            $info = json_decode(file_get_contents($filePath));

            if( $info->autoload ){ // instatiate autoload extensions
                $extension = new $className($filePath);
                $this->data["$className"] = $extension;
            }

        }

    }





}