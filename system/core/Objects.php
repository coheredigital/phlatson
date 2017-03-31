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
    // TODO: move to congfig or app root???
    protected $rootFolders = [
        "site" => "site/",
        "system" =>  "system/"
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




    protected function findDataFileByName($name): string
    {
        // loop through the possible root data folders
        foreach ($this->rootFolders as $folder) {
            $folder = Filter::path( ROOT_PATH . $folder . $this->rootFolder ) . $name ;
            $file = "{$folder}/data.json";
            if (file_exists($file)) return $file;
        }

        return '';
    }

    /**
     * preloads the available data directories / files into '$this->data' using getFileList()
     * @param  string $path the location to be searched
     */
    protected function preloadFileList($path = null)
    {
        $this->data += $this->getFileList($path);
    }

    /**
     * scans the available data directories and returns the found array
     * key : basename of folder
     * value : path to data file
     * @param  string $path the location to be searched
     */
    protected function getFileList($path = null): array
    {

        if(is_null($path)) $path = $this->path;

        if ( !file_exists($path) ) {
            throw new FlatbedException("Cannot get file list, invalid path: {$path}");
        }

        $path = Filter::path($path);


        $folders = glob( $path . "*", GLOB_ONLYDIR);

        $fileList = [];
        foreach ($folders as $folder) {
            $folder = Filter::path($folder);
            $name = basename($folder);
            $fileList["$name"] = $folder . "data.json";
        }
        return $fileList;
    }


    /**
     * @return ObjectCollection
     *
     * Get all valid objects from $this rootPath
     *
     */
    public function all()
    {
        $this->preloadFileList();
        $collection = new ObjectCollection();

        foreach ($this->data as $key => $value) {
            $object = $this->getObject($key);
            $collection->add($object);
        }
        return $collection;
    }



    public function __set( string $key, $value)
    {
        $this->set($key, $value);
    }

    public function set( string $key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * get the singular object type by it uri/name
     * @param  string $name the name or uri that points to the object relative to its storage folder
     * @return Object
     */
    public function get( string $uri )
    {
        // normalize the query to avoid errors
        $uri = Filter::uri($uri);

        // get the file if it exists
        if (!$file = $this->findDataFileByName($uri)) {
            return false;
        }
        return new $this->singularName($file);

    }

    /**
     * get the singular object type by absolute path
     * @param  string $path path to look for a data JSON file that describe a Flatbed Object
     * @return Object
     */
    public function getByPath( $path )
    {
        $file = Filter::path($path) . "data.json";
        return $this->getByFile($file);
    }

    /**
     * get the singular object type by absolute file path
     * @param  string $file the data JSON file that describes the Flatbed Object
     * @return Object
     */
    public function getByFile( $file )
    {
        // get the file if it exists
        if (!is_file($file)) {
            return false;
        }
        return new $this->singularName($file);
    }

    protected function getObject($name)
    {
        // get the file if it exists
        if (!$file = $this->findDataFileByName($name)) {
            return false;
        }
        return new $this->singularName($file);
    }

}
