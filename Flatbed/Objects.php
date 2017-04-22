<?php
namespace Flatbed;


abstract class Objects extends Flatbed
{

    const SINGULAR_CLASSNAME = '';
    public $data = [];
    public $cache = [];

    protected $url;
    protected $path;

    protected $systemUrl;
    protected $systemPath;

    // array to store a set of paths to check for data for this object
    // all values are relative to the site root and require a key
    // TODO: move to config or app root???
    protected $rootFolders = [
        "site" => "site" . DIRECTORY_SEPARATOR,
        "system" =>  "system" . DIRECTORY_SEPARATOR
    ];

    // the folder within the site and system paths to check for items ex: fields, templates, etc
    protected $rootFolder;

    // used to identify the singular version $this relates to
    protected $singularName;

    public function __construct()
    {
        // store paths and urls
        $this->path = ROOT_PATH . "site" . DIRECTORY_SEPARATOR . $this->rootFolder . DIRECTORY_SEPARATOR;
        $this->systemPath = ROOT_PATH . "system" . DIRECTORY_SEPARATOR . $this->rootFolder . DIRECTORY_SEPARATOR;
        $this->url = ROOT_URL . "site/{$this->rootFolder}/";
        $this->systemUrl = ROOT_URL . "system/{$this->rootFolder}/";
    }


    /**
     * preloads the available data directories / files into '$this->data' using getFileList()
     * @param  string $path the location to be searched
     */
    protected function preloadFileList( ?string $path = null)
    {
        foreach ($this->rootFolders as $folder) {
            $path = ROOT_PATH . $folder . $this->rootFolder . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
            $this->data += $this->getFileList($path);
        }
    }

    /**
     * scans the available data directories and returns the found array
     * key : basename of folder
     * value : path to data file
     * @param  string $path the location to be searched
     */
    protected function getFileList($path): array
    {
        if (!file_exists($path)) {
            throw new Exceptions\FlatbedException("Cannot get file list, invalid path: {$path}");
        }


    
        $folders = glob( $this->path . "*", GLOB_ONLYDIR | GLOB_NOSORT);

        $fileList = [];
        foreach ($folders as $folder) {
            $name = basename($folder);
            $fileList["$name"] = $folder . DIRECTORY_SEPARATOR . "data.json";
        }
        return $fileList;
    }


    /**
     * instantiates a new Object of the set singular type
     * @param  strin $name the name of the new object that will be used once it is saved
     * @return Object       [description]
     */
    public function new(string $name) : Object
    {
        $object = new $this->singularName;
        $object->name = $name;
        // $object->parent = $parent;
        return $object;
    }

    /**
     * when provided a name will find an existing valid data file
     * @param  string $name valid system name
     * @return string       filname path
     */
    protected function getDataFile(string $name): string
    {
        $name = trim($name, "/\\");
        // loop through the possible root data folders
        foreach ($this->rootFolders as $folder) {
            $folder = ROOT_PATH . $folder . $this->rootFolder . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
            $file = "{$folder}data.json";

            // return file on first match to break loop
            if (file_exists($file)) {
                return $file;
            }
        }

        return '';
    }




    /**
     * @return ObjectCollection
     *
     * Get all valid objects from $this rootPath
     *
     */
    public function all()
    {
        $this->preloadFileList($this->systemPath);
        $this->preloadFileList();
        $collection = new ObjectCollection();

        foreach ($this->data as $key => $value) {
            if (!$object = $this->get($key)) continue;
            $collection->append($object);
        }
        return $collection;
    }

    /**
     * give property access to all get() variables
     * @param  string $name
     * @return mixed
     */
    final public function __get( string $name)
    {
        switch ($name) {
            case 'systemUrl':
            case 'systemPath':
                return $this->{$name};
            default:
                return parent::__get($name);
        }
    }


    /**
     * get the singular object type by it uri/name
     * @param  string $name the name or uri that points to the object relative to its storage folder
     * @return Object
     */
    public function get(string $uri) : ?Object
    {

        // get the file if it exists
        if (!$file = $this->getDataFile($uri)) {
            return null;
        }

        // store found object for future reference
        $class = "Flatbed\\$this->singularName";
        return new $class($file);

        return $this->cache[$uri];
    }

    /**
     * get the singular object type by absolute path
     * @param  string $path path to look for a data JSON file that describe a Flatbed Object
     * @return Object
     */
    public function getByPath($path)
    {
        $file = $path . DIRECTORY_SEPARATOR . "data.json";
        return $this->getByFile($file);
    }

    /**
     * get the singular object type by absolute file path
     * @param  string $file the data JSON file that describes the Flatbed Object
     * @return Object
     */
    public function getByFile($file)
    {
        // get the file if it exists
        if (!file_exists($file)) {
            return null;
        }
        $class = "Flatbed\\$this->singularName";
        return new $class($file);
    }

}
