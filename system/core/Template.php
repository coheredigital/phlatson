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

        $this->setUnformatted("template", "template");

    }

    public function hasField($name){
        return isset($this->data["fields"][$name]);
    }

}
