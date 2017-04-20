<?php

class Template extends Object
{

    const DATA_FOLDER = 'templates';

    public $parent; // the object this template belongs to
    public $defaultFields = ['title','fields', 'name','view'];


    function __construct($file = null)
    {

        parent::__construct($file);

        $this->skippedFields = array_merge($this->skippedFields, [
            "template"
        ]);

        $this->data("template", "template");

    }

    public function hasField($name){
        return isset($this->data["fields"][$name]);
    }

    /**
     * get the view file associated wioth this template
     * default name to look for is {$template->name}.php
     * @param  string $name 
     * @return sting  file path
     */
    public function getView( string $name = '')
    {
        $filename = $name ? $name : $this->name;
        return $this('views')->get($filename);
    }

    /**
     * get the view file associated wioth this template
     * default name to look for is {$template->name}.php
     * @param  string $name 
     * @return sting  file path
     */
    public function getController()
    {

        $file = SITE_PATH . "controllers" . DIRECTORY_SEPARATOR . "{$this->name}.php";
        if (is_file($file)) return $file;

        $file = SYSTEM_PATH . "controllers" . DIRECTORY_SEPARATOR . "{$this->name}.php";
        if (is_file($file)) return $file;

        return null;
    }

    public function get( string $name)
    {
        switch ($name) {
            case 'view':
                return $this->getView();
            case 'controller':
                return $this->getController();
            case 'objectType': // protected / private variable that should have public get
                return $this->{$name};
            default:
                return parent::get($name);
        }

    }

}
