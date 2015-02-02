<?php

class Template extends Object
{

    public $master; // the object this template belongs to
    protected $rootFolder = "templates";
    public $defaultFields = array("parent");

    public function setReference($object)
    {
        $this->master = $object;
    }

    public function get($name)
    {
        switch ($name) {
            case 'template':
                return app("templates")->get("template");
            case 'layout':
                $layoutFile = app('config')->paths->layouts . $this->name . ".php";
                return $layoutFile;
            default:
                return parent::get($name);
        }
    }

    public function hasField($name){
        return isset($this->data["fields"][$name]);
    }

}