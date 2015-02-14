<?php

abstract class Objects
{

    use hookable;

    public $data = array();
    protected $count;

    // the folder within the site and system paths to check for items ex: fields, templates, etc
    protected $rootFolder;
    protected $rootPath;

    protected $recursiveList = false; // flag indicates whether root data should containrecursive results or just top level

    // used to identify the singular version of the represent an array
    // Ex: Fields = Field | Templates = Template , fairly straight forward, used primarily to make code reusable
    protected $singularName;

    public function __construct()
    {

        $this->rootPath = normalizePath(app('config')->paths->site . $this->rootFolder);

        if ($this instanceof Pages) {
            $this->data['/'] = app("config")->paths->pages . "data.json";
        }

    }

    protected function getItem($key)
    {

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        $root = normalizePath(app('config')->paths->site . $this->rootFolder);
        $path = normalizePath($root . $key);

        $file = $path . "data.json";
        if (is_file($file)) {
            $this->set($key, $file);
        }

    }


    // return a key => value array of valid object locations
    protected function getObjectList($directory = null)
    {

        $siteRootPath = app('config')->paths->site . $this->rootFolder;
        $siteRootPath = normalizePath($siteRootPath);

        $sitePathCheck = $siteRootPath . $directory;
        $sitePathCheck = normalizePath($sitePathCheck);

        if ($this->isValidPath($sitePathCheck)) {
            $this->getList($siteRootPath, $sitePathCheck);
        }

    }

    protected function getList($depth = 1)
    {

        $iterator = new RecursiveDirectoryIterator($this->rootPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        $iterator->setMaxDepth($depth);

        foreach ($iterator as $item) {

            $itemPath = normalizePath($item->getPathName());

            if(!$this->isValidObject($item)) continue;

            $directory = $this->getItemDirectory($item);

            // add root items for pages to allow home selection
            $this->data["$directory"] = $itemPath;

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

    public function all()
    {
        $this->getObjectList();
        $objectArray = new ObjectCollection();

        foreach ($this->data as $key => $value) {
            $object = $this->getObject($key);
            $objectArray->add($object);
        }

        return $objectArray;
    }

    protected function getObject($key)
    {
        if (!$this->has($key)) {
            $this->getItem($key);
            if (!$this->has($key)) {
                return false;
            }
        }
        $file = $this->data[$key];

        if ($this instanceof Extensions) {
            $object = new $key($file);
        } else {
            $object = new $this->singularName($file);
        }
        return $object;
    }

    protected function getItemDirectory(SplFileInfo $item){
        $path = $item->getPath();
        $filename = $item->getPath();

        $directory = str_replace($this->rootPath, "", $path);
        $directory = str_replace($filename, "", $directory);
        $directory = trim($directory, DIRECTORY_SEPARATOR);
        return normalizeDirectory($directory);
    }

    public function isValidPath($path)
    {
        $path = normalizePath($path);
        if (strpos($path, app("config")->paths->root) !== false) {
            return true;
        }
        return false;
    }

    protected function isValidObject(SplFileInfo $item){
        return ($item->getFileName() == "data.json");
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


    public function has($key)
    {
        $key = normalizeDirectory($key);
        return array_key_exists($key, $this->data);
    }



}
