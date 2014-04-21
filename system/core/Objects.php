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

    public function __get($key)
    {
        return $this->get($key);
    }

    public function get($name)
    {

        if (is_object($name)) { // stringify $object
            $name = (string)$name;
        }

        if (!$this->has($name)) {
            return false;
        }
        $object = new $this->singularName($name);
        if(!is_subclass_of($object,"Object")){
            throw new Exception("Failed to retrieve valid object subclass : {$this->singularName}");
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
