<?php

abstract class Objects extends Core implements IteratorAggregate, Countable
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
        $this->data = $this->setupData();
    }

    // load available objects into $data array()
    protected function setupData()
    {

        $objects = $this->getObjectList("system");

        $siteObjects = $this->getObjectList();
        if(is_array($siteObjects)){
            $objects = array_merge($objects, $siteObjects);
        }

        return $objects;

    }

    // return a key => value array of valid object locations
    public function getObjectList($root = "site"){

        $dataFile = "data.json";

        $path = $this->api('config')->paths->{$root} . $this->rootFolder;
        $path = realpath( $path );

        if ( !$path ) return array();

        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $item) {

            $itemPath = realpath($item->getPathName());
            $itemFilename = $item->getFileName();

            if($itemFilename != $dataFile) continue;

            $directory = str_replace($path,"",$itemPath);
            $directory = str_replace($itemFilename,"",$directory);
            $directory = trim($directory,DIRECTORY_SEPARATOR);
            $directory = normalizeDirectory($directory);

            // add root items for pages to allow home selection
            $array["$directory"] = $itemPath;

        }

        return $array;

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

       $array = array();
       foreach ($this->data as $key => $value) {
           $array[] = $this->$key;
       }
       return $array;

    }

    public function filter($array){

        $objects = $this->all();

        foreach ($array as $key => $value) {

            $objects = array_filter($objects, function($object) use($key, $value){

                // TODO : this should actually fail / throwException
                if ( !$object->{$key}) return true;

                $what = $object->{$key};
                return $what == $value;
            });

        }
        return $objects;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function get($query)
    {

        // normalize the query
        $query = normalizeDirectory($query);


        if (!$this->has($query)) {
//            return false;
            // TODO : this should actually fail / throwException, disbaled for now because failing to much
             throw new Exception("{$this->singularName} ('{$query}') does not exist in {$this}");
        }

        $file = $this->getFilename($query);

        $object = new $this->singularName($file, $query);
        if(!$object instanceof $this->singularName){
            throw new Exception("Failed to retrieve valid object subclass : {$this->singularName} : request - $query");
        }
        return $object;

    }

    protected function getFilename($key){
        return $this->data[$key];
    }

    public function has($key){

        $key = normalizeDirectory($key);
        return array_key_exists($key, $this->data);
    }

    public function getIterator()
    {
        return new ArrayObject($this->data);
    }

    public function count()
    {
        if (!isset($this->count)) {
            $this->count = count($this->data);
        }
        return $this->count;
    }

    public function __unset($key)
    {
        $this->remove($key);
    }

}
