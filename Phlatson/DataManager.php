<?php

use Phlatson\DataFile;

class DataManager
{
    // /**
    //  * Manages finding, updating, moving, deleting of DataFile(s)
    //  */
    // protected string $rootPath;
    // protected $cache = [];

    public function __construct(string $path)
    {
        // setup default config and import site config
        $this->rootPath = \rtrim($path, '/');
    }

    public function map(Type $var = null)
    {
        // code...
    }
}
