<?php

namespace Phlatson;

class Finder
{
    protected App $app;
    protected string $rootPath;
    protected array $mappings = [];

    public function __construct(App $app, string $rootPath)
    {
        $this->app = $app;
        $this->rootPath = $rootPath;
    }

    public function getRootPath(): string
    {
        return $this->app->path();
    }

    public function map(string $classname, string $path): self
    {
        // validate class
        if (!class_exists("\Phlatson\\{$classname}")) {
            throw new \Exception("Class ($classname) does not exist, cannot be used for path mappings");
        }

        // normalize the path
        if ($path && !file_exists($path)) {
            throw new \Exception("Path ({$path}) does not exist, cannot be used as site data");
        }

        $this->mappings[$classname][$path] = new Folder($this->app, $path);

        return $this;
    }

    public function getDataFile(string $path, string $filename = 'data'): ?DataFile
    {
        $dataFile = new DataFile("{$path}{$filename}.json");

        return $dataFile;
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

    public function getDataFor(string $classname, string $uri): ?DataFile
    {
        $uri = \trim($uri, '/');

        // validate class
        // TODO: This could be cleaner
        if (!class_exists("\Phlatson\\$classname")) {
            throw new \Exception("Class ($classname) does not exist, cannot be used to get data");
        }

        foreach ($this->mappings[$classname] as $rootFolder) {
            if ($rootFolder->hasChild($uri)) {
                $folder = $rootFolder->find($uri);
            }
        }

        // TODO: support variable file
        $file = $folder->file('data.json');

        return $file;
    }

    public function get(string $classname, $path): ?DataObject
    {
        // get data object
        if (!$data = $this->getDataFor($classname, $path)) {
            return null;
        }

        // load custom class if detected
        // this is need for Fieldtypes, possibly more later
        // if ($classFile = $this->getCustomClassFile($classname, $path)) {
        //     require_once $classFile;
        //     $classname = "\Phlatson\\$path";
        // } else {
        $classname = "\Phlatson\\$classname";
        // }

        $object = new $classname($path, $this->app);
        $object->setData($data);

        return $object;
    }

    public function getPaths(string $classname): array
    {
        if (!isset($this->mappings[$classname])) {
            throw new \Exception("Path mapping for ($classname) not found, cannot be used to get data");
        }

        return array_reverse($this->mappings[$classname]);
    }

    public function getView(string $uri): View
    {
        // get mappings paths
        $folders = $this->getPaths('View');

        foreach ($folders as $folder) {
            $path = \rtrim($folder->path(), '/');
            $file = "$path/$uri.php";
            if (\file_exists($file)) {
                $view = new View($path, $uri);
                break;
            }
        }

        return $view;
    }
}
