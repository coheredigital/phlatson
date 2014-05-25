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

    // status flags
    protected $dataLoaded = false;

    final public function __construct()
    {
        $this->data = $this->setupData();
    }

    // load available objects into $data array()
    protected function setupData()
    {
        if ($this->root) {

            $objects = glob($this->api('config')->paths->site . $this->root . "*", GLOB_ONLYDIR);

            // get system items
            if ($this->checkSystem) {
                $systemObjects = glob($this->api('config')->paths->system . $this->root . "*", GLOB_ONLYDIR);
                $objects = array_merge($objects, $systemObjects);
            }

            // assign key => value pairs
            $dataArray = array();
            foreach ($objects as $path) {
                $name = basename($path);
                $dataArray["$name"] = $path;
            }
            return $dataArray;

        }

        return null;
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



        // handle single get request
        if(is_string($query)){

            if (!$this->has($query) && !$this->allowRootRequest) {
                return false;
                // throw new Exception("Object ({$url}) does not exist in {$this->singularName}");
            }


            $object = new $this->singularName($query);
            if(!$object instanceof $this->singularName){
                throw new Exception("Failed to retrieve valid object subclass : {$this->singularName} : request - $query");
            }
            return $object;
        }
        else if(is_array($query)){
            $arrayType = "{$this->singularName}Array";
            $objectArray = new $arrayType;
            foreach($query as $url){
                $object = $this->get($url);
                $objectArray->add($object);

            }
            return $objectArray;
        }



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
