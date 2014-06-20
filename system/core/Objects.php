<?php

abstract class Objects extends Core implements IteratorAggregate, Countable
{

    public $data = array();
    protected $count;

    // the folder within the site and system paths to check for items ex: fields, templates, etc
    protected $root;
    protected $checkSystem = true; // flag whether or not to load values from identical forlder in system directory (ex: false for users | true for fields | defaults to true)
    protected $recursiveList = false; // flag indicates whether root data should containrecursive results or just top level
    protected $allowRootRequest = false;
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
        if ($this->root) {

            $siteObjects = $this->getObjectList();

            // get system items
            if ($this->checkSystem) {

                $systemObjects = $this->getObjectList("system");
                if(is_array($systemObjects))
                    $objects = array_merge($siteObjects, $this->getObjectList("system"));
            }

            return $objects;

        }

        return null;
    }

    // return a key => value array of valid object locations
    public function getObjectList($root = "site", $dataFile = "data.json"){

        $path = realpath( $this->api('config')->paths->{$root} . $this->root );

        if ( !$path ) return array();

        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $item) {
            // Note SELF_FIRST, so array keys are in place before values are pushed.
            $itemPath = realpath($item->getPathName());

            $itemParts = explode(DIRECTORY_SEPARATOR, $item);
            $itemFilename = end($itemParts);

            if($itemFilename != $dataFile) continue;

            $directory = str_replace($path,"",$itemPath);
            $directory = str_replace($itemFilename,"",$directory);
            $directory = trim($directory,DIRECTORY_SEPARATOR);


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

        if( !$query )  throw new Exception("Method: get() required parameter missing");

        if (!$this->has($query) && !$this->allowRootRequest) {
             throw new Exception("{$this->singularName} ('{$query}') does not exist in {$this}");
        }

        $object = new $this->singularName($query);
        if(!$object instanceof $this->singularName){
            throw new Exception("Failed to retrieve valid object subclass : {$this->singularName} : request - $query");
        }
        return $object;

    }

    public function has($key){
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
