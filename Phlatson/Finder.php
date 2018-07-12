<?php 

namespace Phlatson;

class Finder
{
    protected $root;

    public function __construct(string $path)
    {
        // normalize the path
        $path = Sanitizer::path($path);

        if (!file_exists($path)) {
            throw new Exceptions\PhlatsonException("Path ($path) does not exist, cannot be used as site data");
        }

        $this->root = $path;
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

        if (!file_exists($file)) {
            throw new Exceptions\PhlatsonException("File ($file) does not exist");
        }

        return $file;
    }

    public function get(string $path) : ?DataObject
    {


        $jsonObject = $this->getData($path);

        $path_parts = explode('/', trim($path, "/"));

        $classname = array_shift($path_parts);
        $classname = ucfirst($classname);
        $classname = substr($classname, 0, -1);
        $classname = "\Phlatson\\$classname";

        $path = implode("/", $path_parts);
        $path = "/$path/";

        $objectType = new $classname();
        $objectType->setData($jsonObject);

        return $objectType;
    }

    public function getData(string $path) : JsonObject
    {
        if (!$file = $this->exists($path)) {
            return null;
        }
        $jsonObject = new JsonObject($file);
        return $jsonObject;
    }

    public function getTypeData(string $classname, string $path) : JsonObject
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
