<?php

abstract class Objects extends Flatbed
{

    use hookable;

    public $data = array();
    protected $count;

    protected $url;
    protected $path;

    protected $systemUrl;
    protected $systemPath;

    // the folder within the site and system paths to check for items ex: fields, templates, etc
    protected $rootFolder;
    protected $siteRoot;
    protected $systemRoot;

    // used to identify the singular version $this relates to
    protected $singularName;

    public function __construct()
    {

        $this->siteRoot = Filter::path(ROOT_PATH . "site" . DIRECTORY_SEPARATOR . $this->rootFolder);
        $this->path = Filter::path(ROOT_PATH . "site" . DIRECTORY_SEPARATOR . $this->rootFolder);

        $this->systemRoot = Filter::path(ROOT_PATH . "system" . DIRECTORY_SEPARATOR . $this->rootFolder);
        $this->systemPath = Filter::path(ROOT_PATH . "system" . DIRECTORY_SEPARATOR . $this->rootFolder);

        $this->url = Filter::url(ROOT_URL . "site" . DIRECTORY_SEPARATOR . $this->rootFolder);
        $this->systemUrl = Filter::url(ROOT_URL . "system" . DIRECTORY_SEPARATOR . $this->rootFolder);



    }

    public function create($name)
    {
        $field = new $this->singularName;
        $field->name = $name;
        $field->parent = $parent;
        return $field;
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

        $sitePath = Filter::path($this->siteRoot . $name);
        $systemPath = Filter::path($this->systemRoot . $name);

        $siteFile = "{$sitePath}data.json";
        $systemFile = "{$systemPath}data.json";

        if (is_file($systemFile)) {
            $this->set($name, $systemFile);
        }

        if (is_file($siteFile)) {
            $this->set($name, $siteFile);
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

    protected function getFileList($path = null, $depth = 1)
    {

        if(is_null($path)) $path = $this->siteRoot;

        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        $iterator->setMaxDepth($depth);

        foreach ($iterator as $folder) {

            $filePath = Filter::path($folder->getPathName());

            if(!$this->isValidObject($folder)) continue;

            $uri = $this->getItemUri($folder);


            $this->data["$uri"] = $filePath;

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

    protected function getObject($name)
    {
        // get the file if it exists
        if (!$file = $this->getItem($name)) {
            return false;
        }
        return new $this->singularName($file);
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
     * @param $path
     * @return bool
     *
     * Checks that path is withing Objects root path
     *
     */
    protected function isValidPath($path)
    {

        if (strpos($path, $this->siteRoot) !== false) {
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
