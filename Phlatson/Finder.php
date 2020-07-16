<?php

namespace Phlatson;

class Finder
{
    
    protected $root;
    protected $pathMappings = [];

    public function __construct(string $rootPath = '')
    {

        // normalize the path
        if ($rootPath && !file_exists($rootPath)) {
            throw new \Exception("Path ($rootPath) does not exist, cannot be used as site data");
        }

        $rootPath = Sanitizer::path($rootPath);

        $this->root = $rootPath;
    }


    final public function addPathMapping(string $classname, string $path): self
    {

        // validate class
        if (!class_exists("\Phlatson\\{$classname}")) {
            throw new \Exception("Class ($classname) does not exist, cannot be used for path mappings");
        }

        // normalize the path
        if ($path && !file_exists($this->root . $path)) {
            throw new \Exception("Path ($path) does not exist, cannot be used as site data");
        }

        $this->pathMappings[$classname][] = $path;

        return $this;
    }


    public function getDataFile(string $path, string $filename = 'data'): ?JsonObject
    {
        $jsonObject = new JsonObject("{$path}{$filename}.json");
        return $jsonObject;
    }

    protected function getData(string $classname, string $uri): JsonObject
    {

        $uri = \trim($uri, '/');

        // validate class
        // TODO: This could be cleaner
        if (!class_exists("\Phlatson\\$classname")) {
            throw new \Exception("Class ($classname) does not exist, cannot be used to get data");
        }

        // get mappings paths
        $paths = $this->getPaths($classname);

        foreach ($paths as $path) {

            $path = \trim($path, '/');
            $folder = "{$this->root}$path/$uri/";
            if (\file_exists($folder)) {
                $data = $this->getDataFile($folder);
                break;
            }
        }

        return $data;
    }

    public function getType(string $classname, $path, string $file = "data.json"): ?DataObject
    {
        // get data object
        $jsonObject = $this->getData($classname,$path);

        $classname = "\Phlatson\\$classname";
        $dataobject = new $classname();
        $dataobject->setData($jsonObject);

        return $dataobject;
    }

    public function getPaths(string $classname): array
    {

        if (!isset($this->pathMappings[$classname])) {
            throw new \Exception("Path mapping for ($classname) not found, cannot be used to get data");
        }

        return $this->pathMappings[$classname];
    }


}
