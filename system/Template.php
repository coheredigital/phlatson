<?php

class Template extends Object
{

    public $parent; // the object this template belongs to
    protected $rootFolder = "templates";
    public $defaultFields = ['title','fields', 'name'];


    function __construct($file = null)
    {

        parent::__construct($file);

        $this->defaultFields = array_merge($this->defaultFields, [
            "title",
            "fields"
        ]);

        $this->skippedFields = array_merge($this->skippedFields, [
            "template"
        ]);

        $this->setUnformatted("template", "template");

    }

    public function get($name)
    {
        switch ($name) {
            case 'view':
                $viewFile = $this->api('config')->paths->views . $this->name . ".php";
                return $viewFile;
            default:
                return parent::get($name);
        }
    }

    public function hasField($name){
        return isset($this->data["fields"][$name]);
    }

}