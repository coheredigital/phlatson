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
        // manually add the special case of the home page
        if ($this instanceof Pages) {
            $this->data['/'] = new Page(api("config")->paths->pages . "data.json", "/");
        }

    }

    // load available objects into $data array()

    // return a key => value array of valid object locations
    protected function getObjectList($path = null){

        $root = normalizePath( $this->api('config')->paths->system . $this->rootFolder );
        if( $this->isValidPath( normalizePath( $root . $path ) ) ){
            $this->getList($root, $path);
        }

        $root = normalizePath( $this->api('config')->paths->site . $this->rootFolder );
        if( $this->isValidPath( normalizePath( $root . $path ) ) ){
            $this->getList($root, $path);
        }

    }

    protected function getList($root, $query){

        $queryPath = normalizePath( $root . $query );

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

    public function get($query)
    {
        // normalize the query to avoid error in the case of a page request that might get passed as ( /about-us/staff ) but should be ( about-us/staff )
        $query = normalizeDirectory($query);
        return $this->getObject($query);
    }

    protected function getObject($key){

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

            if ( $this instanceof Extensions ) {
                $object = new $key($file);
            }
            else {
                $object = new $this->singularName($file, $key);
                $this->data[$key] = $object; // we don't add extensions so we can make multiple instances
            }

            return $object;
        }

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
