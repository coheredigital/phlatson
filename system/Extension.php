<?php


class Extension extends Object
{

    protected $rootFolder = "extensions";
    protected $requiredElements = ['title','type'];

    protected $instantiated = false;


    final public function __construct($file = null)
    {

        // TODO: this is require because of the way router handles Class.method route callbacks
        // File is not being passed so not enough can be determined about the extension (path/file)
        if(is_null($file)){
            $reflection = new ReflectionClass($this);
            $directory = dirname($reflection->getFileName()) . DIRECTORY_SEPARATOR;
            $file = $directory . "data.json";
        }

        parent::__construct($file);
        $this->setup();
    }


    protected function setup()
    {
    }

}