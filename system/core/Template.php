<?php

class Template extends Object
{

    const DATA_FOLDER = 'templates';

    public $parent; // the object this template belongs to
    public $defaultFields = ['title','fields', 'name','view','modified'];


    function __construct($file = null)
    {

        parent::__construct($file);



        // // $this->data("template", "template");

        // foreach ($this->defaultFields as $name) {
        //     if (!$name) continue;
        //     $this->addField($name);
        // }

    }

    public function hasField($name){
        
        return isset($this->data["fields"][$name]);
    }

    public function addField( string $name )
    {

        if (!$field = $this->api('fields')->get($name)) {
            return;
        }

        return $this->data["fields"][$name];
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
            case 'template':
                $template = $this->api('templates')->get('template');
                $template->parent = $this;
                return $this->api('templates')->get('template');
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
