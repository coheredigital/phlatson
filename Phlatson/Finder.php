<?php

namespace Phlatson;

class Finder
{

    // protected $root;
    protected $pathMappings = [];


    final public function addPathMapping(string $classname, string $path): self
    {

        // validate class
        if (!class_exists("\Phlatson\\{$classname}")) {
            throw new \Exception("Class ($classname) does not exist, cannot be used for path mappings");
        }

        $path = trim($path, "/");

        // normalize the path
        if ($path && !file_exists($path)) {
            throw new \Exception("Path ({$path}) does not exist, cannot be used as site data");
        }

        $this->pathMappings[$classname][] = $path;

        return $this;
    }

    public function getDataFile(string $path, string $filename = 'data'): ?JsonObject
    {
        $jsonObject = new JsonObject("{$path}{$filename}.json");
        return $jsonObject;
    }

    public function hasDataFor(string $classname, string $uri): bool
    {

        $uri = \trim($uri, '/');

        // get mappings paths
        $paths = $this->getPaths($classname);

        foreach ($paths as $path) {
            $folder = "$path/$uri/";
            if (\file_exists($folder)) {
                return true;
            }
        }

        return false;
    }

    // TODO: re-evaluate
    public function getCustomClassFile(string $classname, string $uri): ?string
    {

        $uri = \trim($uri, '/');

        // get mappings paths
        $paths = $this->getPaths($classname);

        foreach ($paths as $path) {

            $path = \trim($path, '/');
            $file = "{$path}/{$uri}/{$uri}.php";
            if (\file_exists($file)) {
                return $file;
            }
        }

        return null;
    }
    public function requireCustomClassFor(string $classname, string $uri): bool
    {

        $uri = \trim($uri, '/');

        // get mappings paths
        $paths = $this->getPaths($classname);

        foreach ($paths as $path) {

            $path = \trim($path, '/');
            $file = "{$this->root}{$path}/{$uri}.php";
            if (\file_exists($file)) {
                return true;
            }
        }

        return false;
    }

    public function getDataFor(string $classname, string $uri): JsonObject
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

            $path = \rtrim($path, '/');
            $folder = "$path/$uri/";
            if (\file_exists($folder)) {
                $data = $this->getDataFile($folder);
                break;
            }
        }

        return $data;
    }

    public function get(string $classname, $path): ?DataObject
    {
        // get data object
        if (!$this->hasDataFor($classname, $path)) {
            return null;
        }

        // load custom class if detected
        if ($classFile = $this->getCustomClassFile($classname, $path)) {
            require_once $classFile;
            $classname = "\Phlatson\\$path";
        }
        else {
            $classname = "\Phlatson\\$classname";
        }

        $object = new $classname($path, $this);

        return $object;
    }




    public function getPaths(string $classname): array
    {

        if (!isset($this->pathMappings[$classname])) {
            throw new \Exception("Path mapping for ($classname) not found, cannot be used to get data");
        }

        return array_reverse($this->pathMappings[$classname]);
    }

    /**
     * Allows better readability for DataObject retrieval
     *
     * @param string $name
     * @param [type] $arguments
     * @return DataObject|null
     */
    public function __call(string $name, $arguments): ?DataObject
    {
        // TODO: is a test, and a mess, improve or remove
        $startsWith = substr($name, 0, 3);

        if ($startsWith !== 'get') {
            throw new Exception("No method $name");
        }

        $typeName = str_replace($startsWith, "", $name);

        return $this->get($typeName, $arguments[0]);

    }

}
