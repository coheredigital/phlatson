<?php


abstract class Extension extends Object
{

    protected $rootFolder = "extensions/";

    public function __construct($file)
    {
        parent::__construct($file);
        $this->setup();
    }

    protected function setup()
    {
    }

    public function get($name)
    {
        switch ($name) {
            case 'name':
            case 'directory':
                return $this->get("className");
            case 'type':
                return "Extension";
            default:
                return parent::get($name);
                break;
        }
    }


}