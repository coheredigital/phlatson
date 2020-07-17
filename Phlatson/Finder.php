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

    public function hasDataFor(string $classname, string $uri): bool
    {

        $uri = \trim($uri, '/');

        // get mappings paths
        $paths = $this->getPaths($classname);

        foreach ($paths as $path) {

            $path = \trim($path, '/');
            $folder = "{$this->root}$path/$uri/";
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
            $file = "{$this->root}{$path}/{$uri}/{$uri}.php";
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

            $path = \trim($path, '/');
            $folder = "{$this->root}$path/$uri/";
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

        $jsonObject = $this->getDataFor($classname,$path);
        

        // load custom class if detected
        if ($classFile = $this->getCustomClassFile($classname, $path)) {
            require_once $classFile;
            $classname = "\Phlatson\\$path";
        }
        else {
            $classname = "\Phlatson\\$classname";
        }

        $object = new $classname();
        
        if ($object instanceof DataObject ) {
            $object->setData($jsonObject);
        }

        return $object;
    }

    public function getPaths(string $classname): array
    {

        if (!isset($this->pathMappings[$classname])) {
            throw new \Exception("Path mapping for ($classname) not found, cannot be used to get data");
        }

        return $this->pathMappings[$classname];
    }


}
