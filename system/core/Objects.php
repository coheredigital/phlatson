<?php

abstract class Objects extends Core
{

    public $data = array();
    protected $count;

    // the folder within the site and system paths to check for items ex: fields, templates, etc
    protected $rootFolder;

    protected $recursiveList = false; // flag indicates whether root data should containrecursive results or just top level

    // used to identify the singular version of the represent an array
    // Ex: Fields = Field | Templates = Template , fairly straight forward, used primarily to make code reusable
    protected $singularName;

    final public function __construct()
    {
        // manually add the special case of the home page
        if ($this instanceof Pages) {
            $this->data['/'] = api("config")->paths->pages . "data.json";
        }

    }

    protected function getItem($key){

        if ( isset($this->data[$key]) ) return $this->data[$key];

        $root = normalizePath( $this->api('config')->paths->site . $this->rootFolder );
        $path = normalizePath( $root . $key );

        if( !$this->isValidPath( $path ) ){
            $root = normalizePath( $this->api('config')->paths->system . $this->rootFolder );
            $path = normalizePath( $root . $key );

            if( !$this->isValidPath( $path ) ) return false;
        }

        $file = $path . "data.json";
        if(is_file($file)){

            $this->set($key, $file);

        }

    }


    // return a key => value array of valid object locations
    protected function getObjectList($path = null){


        $systemPath =  $this->api('config')->paths->system . $this->rootFolder;
        $systemPath = normalizePath( $systemPath );

        $systemPathCheck =  $systemPath . $path;
        $systemPathCheck = normalizePath( $systemPathCheck );

        if( $this->isValidPath($systemPathCheck) ){
            $this->getListRecursive($systemPath, $systemPathCheck);
        }


        $sitePath =  $this->api('config')->paths->site . $this->rootFolder;
        $sitePath = normalizePath( $sitePath );

        $sitePathCheck =  $sitePath . $path;
        $sitePathCheck = normalizePath( $sitePathCheck );

        if( $this->isValidPath($sitePathCheck) ){
            $this->getListRecursive($sitePath, $sitePathCheck);
        }

    }

    protected function getListRecursive($root, $path, $depth = 1){

        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
        $iterator->setMaxDepth($depth);

        foreach ($iterator as $item) {

            $itemPath = $item->getPathName();
            $itemPath = normalizePath($itemPath);
            $itemFilename = $item->getFileName();

            if($itemFilename != "data.json") continue;

            $directory = str_replace($root, "", $itemPath);
            $directory = str_replace($itemFilename,"",$directory);
            $directory = trim($directory,DIRECTORY_SEPARATOR);
            $directory = normalizeDirectory($directory);

            // add root items for pages to allow home selection
            $this->data["$directory"] = $itemPath;

        }

    }


    protected function getList($root, $query){

        $dir = opendir($queryPath);
        while(($name = readdir($dir)) !== false)
        {

            if ( $name == '.' or $name == '..' ) continue;

            $directory = $query . $name;
            $file = normalizePath( $queryPath . $directory ) . "data.json";

            if ( !is_file($file)) continue;


            $this->data[$directory] = $file;
        }

        closedir($dir);


    }



    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function all()
    {
        $this->getObjectList();
        $arrayType = $this->singularName . "Array";

        $objectArray = new ObjectArray();

        foreach ($this->data as $key => $value){
            $object = $this->getObject($key);
            $objectArray->add($object);
        }

        return $objectArray;
    }

    protected function getObject($key){
        if ( !$this->has($key) ){
            $this->getItem($key);
            if ( !$this->has($key) ) return false;
        }
        $file = $this->data[$key];

        if ( $this instanceof Extensions ) {
            $object = new $key($file);
        }
        else {
            $object = new $this->singularName($file, $key);
        }
        return $object;
    }


    public function isValidPath($path){
        $path = normalizePath($path);
        if(strpos( $path,  api("config")->paths->root ) !== false){
            return true;
        }
        else{
            return false;
        }
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function get($key)
    {
        // normalize the query to avoid error in the case of a page request that might get passed as ( /about-us/staff ) but should be ( about-us/staff )
        $key = normalizeDirectory($key);

        $object = $this->getObject($key);
        return $object;
    }



    public function has($key){
        $key = normalizeDirectory($key);
        return array_key_exists($key, $this->data);
    }


}
