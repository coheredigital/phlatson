<?php 

namespace Phlatson;

class Finder
{
    protected $root;

    protected $type;

    protected $pathMappings = [
        'Page' => [
            '/site/pages/'
        ],
        'Model' => [
            '/site/models/'
        ],
        'Config' => [
            '/site/config/',
            '/Phlatson/data/config/'
        ],
        'Extension' => [
            '/site/extensions/',
            '/Phlatson/data/extensions/'
        ],
    ];

    protected $paths = [];

    public function __construct(string $path = '')
    {
        // normalize the path
        if ($path && !file_exists($path)) {
            throw new \Exception("Path ($path) does not exist, cannot be used as site data");
        }

        $path = Sanitizer::path($path);

        $this->root = $path;
    }

    final public function setType(string $type) : self
    {
        $this->type = $type;
        return $this;
    }

    final public function addPath(string $path) : self
    {
        // $path = Sanitizer::path($path);

        // if (!file_exists($path)) {
        //     throw new \Exception("Path ($path) does not exist, cannot be used as site data");
        // }

        // path as key to prevent duplicates
        $this->paths[$path] = $path;

        return $this;
    }

    public function root() : string
    {
        return $this->root;
    }

    public function getFiles(string $folder) : array
    {
        $path = $this->getPath($folder);
        $files = glob("{$path}*.json");
        return $files;
    }

    public function exists(string $folder)
    {
        $path = $this->getPath($folder);
        $file = "{$path}data.json";

        return !file_exists($file) ? false : $file;
    }

    public function get(string $path) : ?DataObject
    {
        $jsonObject = $this->getData($path);

        $path_parts = explode('/', trim($path, '/'));

        $classname = array_shift($path_parts);
        $classname = ucfirst($classname);
        $classname = substr($classname, 0, -1);
        $classname = "\Phlatson\\$classname";

        $path = implode('/', $path_parts);
        $path = "/$path/";

        $objectType = new $classname();
        $objectType->setData($jsonObject);

        return $objectType;
    }

    public function getFromPaths(string $url) : ?DataObject
    {
        // sanitizer & trim URL
        $url = Sanitizer::url($url);
        $url = ltrim($url, '/');

        foreach ($this->paths as $root) {
            $path = "{$root}{$url}";
            if (file_exists($path)) {
                $jsonObject = $this->getData($path);
                break;
            }
        }

        $path_parts = explode('/', trim($path, '/'));

        $classname = $this->type ? "\Phlatson\\$this->type" : false;

        if (!$classname) {
            $classname = array_shift($path_parts);
            $classname = ucfirst($classname);
            $classname = substr($classname, 0, -1);
            $classname = "\Phlatson\\$classname";
        }

        $path = implode('/', $path_parts);
        $path = "/$path/";

        $objectType = new $classname();
        $objectType->setData($jsonObject);

        return $objectType;
    }

    // TODO: make this work with system data
    public function getData(string $path) : ? JsonObject
    {
        if (!$file = $this->exists($path)) {
            return null;
        }
        $jsonObject = new JsonObject($file);
        return $jsonObject;
    }

    public function getTypeData(string $classname, string $path) : ?JsonObject
    {
        // get data object
        $folder = strtolower($classname);
        // trim just in case and pluralize
        $folder = '/' . trim($folder, '/') . 's';

        $path = Sanitizer::url($path);
        $path = "{$folder}{$path}";
        $jsonObject = $this->getData($path);
        return $jsonObject;
    }

    public function getType(string $classname, $path) : ?DataObject
    {
        // get data object
        $folder = strtolower($classname);
        // trim just in case and pluralize
        $folder = '/' . trim($folder, '/') . 's';

        $path = Sanitizer::url($path);
        $path = "{$folder}{$path}";
        $jsonObject = $this->get($path);

        $classname = ucfirst($classname);
        $classname = "\Phlatson\\$classname";

        $objectType = new $classname();
        $objectType->setData($jsonObject);

        return $objectType;
    }

    protected function sanitizeFolder(string $folder)
    {
        $folder = str_replace(DIRECTORY_SEPARATOR, '/', $folder);
        $folder = trim($folder, '/');
        return "/{$folder}/";
    }

    public function getPath(string $folder)
    {
        $folder = Sanitizer::url($folder);
        return $this->root . ltrim($folder, '/');
    }
}
