<?php

abstract class Objects extends Flatbed
{

    use hookable;

    public $data = [];
    // protected $count;

    protected $url;
    protected $path;

    protected $systemUrl;
    protected $systemPath;

    // array to store a set of paths to check for data for this object
    // all values are relative to the site root and require a key
    protected $dataPaths = [
        "system" => "/system/",
        "site" => "/site/",
    ];

    // the folder within the site and system paths to check for items ex: fields, templates, etc
    protected $rootFolder;

    // used to identify the singular version $this relates to
    protected $singularName;

    public function __construct()
    {
        // store paths and urls 
        $this->path = Filter::path( ROOT_PATH . "site/" . $this->rootFolder );
        $this->systemPath = Filter::path( ROOT_PATH . "system/{$this->rootFolder}");
        $this->url = Filter::url( ROOT_URL . "site/{$this->rootFolder}");
        $this->systemUrl = Filter::url( ROOT_URL . "site/{$this->rootFolder}");
    }


    /**
     * validates and adds a path to the end of the dataPaths set
     * @param string $path [description]
     */
    public function addDataPath( string $path ) {

        if (!file_exists($path)) {
            throw new FlatbedException("The path ($path) deos not exist cannot be used as a data path for {$this->className}");
        }
        $this->dataPaths[] = $path;
    }


    /**
     * instantiates a new Object of the set singular type
     * @param  strin $name the name of the new object that will be used once it is saved
     * @return Object       [description]
     */
    public function create($name): Object
    {
        $object = new $this->singularName;
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $object->set($key, $value);
            }
        }
        else {
            $object->name = $name;
        }
        
        $object->parent = $parent;
        return $object;
    }




    protected function getDataFile($name): string
    {

        $file = "{$this->path}{$name}/data.json";
        if (file_exists($file)) return $file;

        $file = "{$this->systemPath}{$name}/data.json";
        if (file_exists($file)) return $file;

        return '';
    }

    /**
     * @param null $directory
     *
     * Combined function, get file list and instantiate all for use
     *
     */
    protected function getObjectList()
    {
        $this->getFileList();
    }



    protected function getFileList($path = null)
    {

        if(is_null($path)) $path = $this->path;


        $path = Filter::path($path);
        $folders = glob( $path . "*", GLOB_ONLYDIR);

        foreach ($folders as $folder) {
            $folder = Filter::path($folder);
            $name = basename($folder);
            $this->data["$name"] = $folder . "data.json";
        }

    }

    protected function instantiateFileList(){

        foreach ($this->data as $key => $value) {
            $object = $this->getObject($key);
            $this->data[$key] = $object;
        }

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

    /**
     * @return ObjectCollection
     *
     * Get all valid objects from $this rootPath
     *
     */
    public function all()
    {
        $this->getObjectList();
        $collection = new ObjectCollection();

        foreach ($this->data as $key => $value) {
            $object = $this->getObject($key);
            $collection->add($object);
        }

        return $collection;
    }



    protected function getItemUri(SplFileInfo $item){
        $path = $item->getPath();
        $filename = $item->getFilename();
  
        $uri = Filter::uri($path);
        $uri = str_replace($this->path, "", $uri);
        $uri = str_replace($this->systemPath, "", $uri);

        return $uri;
    }

    /**
     * @param SplFileInfo $item
     * @return bool
     *
     * Determine if the current Iterator is a valid Flatbed Object (contains a data.json file)
     *
     */
    protected function isValidObject(SplFileInfo $item){
        return ($item->getFileName() == "data.json");
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function get($name)
    {
        // normalize the query to avoid errors
        $name = Filter::uri($name);

        // get the file if it exists
        if (!$file = $this->getDataFile($name)) {
            return false;
        }
        return new $this->singularName($file);

    }

    protected function getObject($name)
    {
        // get the file if it exists
        if (!$file = $this->getDataFile($name)) {
            return false;
        }
        return new $this->singularName($file);
    }


    /**
     * @param $name
     * @return mixed
     *
     * Get the raw file address on the Flatbed object
     *
     */
    protected function getItem($name)
    {

        if (!$this->has($name)) {
            $this->getDataFile($name);
        }

        return $this->data[$name];

    }

    public function has($key)
    {
        $key = Filter::uri($key);
        return array_key_exists($key, $this->data);
    }



}
