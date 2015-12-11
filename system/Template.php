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
//            case 'template':
//                //  TODO : refactor - the method for defining the master to this template is done manually here
//                // maybe I can automate this like with pages
//                $template = $this->api("templates")->get("template");
//                $template->parent = $this;
//                return $template;
            case 'layout':
                $layoutFile = $this->api('config')->paths->layouts . $this->name . ".php";
                return $layoutFile;
            default:
                return parent::get($name);
        }
    }

    public function hasField($name){
        return isset($this->data["fields"][$name]);
    }

}