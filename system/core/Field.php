<?php

class Field extends Object
{
    const DATA_FOLDER = 'fields';
    protected $attributes = null;
    protected $requiredElements = ["fieldtype","input"];

    const SYSTEM_ROOT = SYSTEM_PATH . "fields" . DIRECTORY_SEPARATOR;
    const SITE_ROOT = SYSTEM_PATH . "fields" . DIRECTORY_SEPARATOR;

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

        $this->setUnformatted("template", "field");

    }

}
