<?php


class Extension extends Object
{

    protected $rootFolder = "extensions";
    protected $requiredElements = ['title', 'type'];

    protected $instantiated = false;


    final public function __construct($file = null)
    {
        // $file = $this->getFile();
        parent::__construct($file);
        $this->setup();
    }

    protected function getFile()
    {
        $reflection = new ReflectionClass($this);
        $directory = dirname($reflection->getFileName()) . DIRECTORY_SEPARATOR;
        $file = $directory . "data.json";
        return $file;
    }

    /**
     * @return boolean
     *
     * Check if extension has configuration settings
     */
    public function isConfigurable()
    {
        if (file_exists("{$this->path}defaultConfiguration.json")) {
            return true;
        }
        return false;
    }


    protected function setup()
    {
    }

}