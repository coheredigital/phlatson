<?php

class Template extends Object
{

    protected $rootFolder = "templates";

    public function setReference($object)
    {
        $this->referenceObject = $object;
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