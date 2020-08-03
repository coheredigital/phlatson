<?php

use Phlatson\DataFile;

class DataManager
{
	// /**
	//  * Manages finding, updating, moving, deleting of DataFile(s)
	//  */
	// protected string $rootPath;
	// protected $cache = [];

	// public function __construct(string $path)
	// {
	// 	// setup default config and import site config
	// 	$this->rootPath = \rtrim($path, '/');
	// }


	final public function addFolder(string $classname, DataFolder $folder): self
    {
        // validate class
        if (!class_exists("\Phlatson\\{$classname}")) {
            throw new \Exception("Class ($classname) does not exist, cannot be used for path mappings");
        }

        $folder = trim($path, '/');

        // normalize the path
        if ($path && !file_exists($path)) {
            throw new \Exception("Path ({$path}) does not exist, cannot be used as site data");
        }

        $this->pathMappings[$classname][] = $path;

        return $this;
    }

}
