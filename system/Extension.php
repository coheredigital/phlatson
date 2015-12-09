<?php


class Extension extends Object
{

    protected $rootFolder = "extensions";
    protected $requiredElements = ['title', 'type'];

    protected $instantiated = false;


    final public function __construct($file = null)
    {
        $file = $this->getFile();
        parent::__construct($file);
        $this->setup();
//        $routesFile = $this->path . "{$this->className}Routes.php";
//        if(file_exists($routesFile)){
//
//            include_once $routesFile;
//            $className = "{$this->className}Routes";
//            $routes = new $className();
//            $routes->setup();
//        }
    }

    protected function getFile()
    {
        $reflection = new ReflectionClass($this);
        $directory = dirname($reflection->getFileName()) . DIRECTORY_SEPARATOR;
        $file = $directory . "data.json";
        return $file;
    }

    protected function setup()
    {
    }

}