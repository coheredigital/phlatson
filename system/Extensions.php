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

            $className = str_replace($root, "", $itemPath);
            $className = trim($className, DIRECTORY_SEPARATOR );
            $className = normalizeDirectory($className);

            // add root items for pages to allow home selection

            $info = $this->getExtensionInfo($itemPath);
            $this->info["$className"] = $info;

            if( $info->autoload ){ // instatiate autoload extensions
                $extension = new $className($filePath);
                $this->data["$className"] = $extension;
            }
//            else{ // otherwise store a reference to there file location
//                $this->data["$className"] = $itemPath . $itemFile;
//            }

        }

    }


    protected function getExtensionInfo($path){
        $file = $path . "info.json";
        $data = json_decode(file_get_contents($file));
        return $data;
    }

    protected function fieldtypes(){
        $array = $this->all()->filter(array(
                "type" => "Fieldtype"
            ));
        return $array;
    }

    public function get($name)
    {
        switch ($name){
            case 'fieldtypes':
                return $this->fieldtypes();
            default:
                return parent::get($name);
        }


    }

}