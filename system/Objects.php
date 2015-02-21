<?php

abstract class Objects
{

    use hookable;

    public $data = array();
    protected $count;

    // the folder within the site and system paths to check for items ex: fields, templates, etc
    protected $rootFolder;
    protected $rootPath;

    // used to identify the singular version $this relates to
    protected $singularName;

    public function __construct()
    {

        $this->rootPath = Filter::path(app('config')->paths->site . $this->rootFolder);

        if ($this instanceof Pages) {
            $this->data['/'] = app("config")->paths->pages . "data.json";
        }

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

    protected function getDataFile($name){

        $path = Filter::path( $this->rootPath . $name);
        $file = "{$path}data.json";
        if (is_file($file)) {
            $this->set($name, $file);
        }
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

    protected function getFileList($depth = 1)
    {

        $iterator = new RecursiveDirectoryIterator($this->rootPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        $iterator->setMaxDepth($depth);

        foreach ($iterator as $item) {

            $itemPath = Filter::path($item->getPathName());

            if(!$this->isValidObject($item)) continue;

            $directory = $this->getItemUri($item);

            // add root items for pages to allow home selection
            $this->data["$directory"] = $itemPath;

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

    protected function getObject($key)
    {
        // get the file if it exists
        if (!$file = $this->getItem($key)) {
            return false;
        }
        return new $this->singularName($file);
    }

    protected function getItemUri(SplFileInfo $item){
        $path = $item->getPath();
        $filename = $item->getFilename();

        $uri = str_replace($this->rootPath, "", $path);
        $uri = str_replace($filename, "", $uri);
        $uri = trim($uri, DIRECTORY_SEPARATOR);
        return Filter::uri($uri);
    }

    /**
     * @param $path
     * @return bool
     *
     * Checks that path is withing Objects root path
     *
     */
    protected function isValidPath($path)
    {

        if (strpos($path, $this->rootPath) !== false) {
            return true;
        }
        return false;
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

    public function get($key)
    {
        // normalize the query to avoid errors
        $key = Filter::uri($key);



        return $this->getObject($key);
    }


    public function has($key)
    {
        $key = Filter::uri($key);
        return array_key_exists($key, $this->data);
    }



}
