<?php

class Template extends Object
{

    protected $fields;
    protected $rootFolder = "templates";



    public function get($name)
    {
        switch ($name) {
            case 'template':
                return api("templates")->get("template");
            case 'layout':
                $layoutFile = api('config')->paths->layouts . $this->name . ".php";
                return $layoutFile;
            default:
                return parent::get($name);
        }
    }

}