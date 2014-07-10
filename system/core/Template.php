<?php

class Template extends Object
{

    protected $referenceObject; // TODO :  bettername
    protected $rootFolder = "templates";

    public function setReference($object){
        $this->referenceObject = $object;
    }

    public function get($name)
    {
        switch ($name) {
            case 'template':
                return api::get("templates")->get("template");
            case 'layout':
                $layoutFile = api::get('config')->paths->layouts . $this->name . ".php";
                return $layoutFile;
            default:
                return parent::get($name);
        }
    }

}