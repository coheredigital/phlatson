<?php

class Field extends Object
{
    protected $baseFolder = "fields";
    protected $attributes = null;
    protected $requiredElements = ["fieldtype","input"];

    function __construct($file = null)
    {

        parent::__construct($file);

        $this->defaultFields = array_merge($this->defaultFields, [
            "fieldtype",
            "input"
        ]);

        $this->skippedFields = array_merge($this->skippedFields, [
            "template"
        ]);
        $this->lockedFields = [
            "template"
        ];

        $this->setUnformatted("parent", "/");
        $this->setUnformatted("template", "field");

    }

}