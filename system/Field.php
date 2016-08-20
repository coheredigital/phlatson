<?php

class Field extends Object
{
    protected $rootFolder = "fields";
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

        $this->setUnformatted("template", "field");

    }
    
    /* ! removed in place of fieldtype */
    // /**
    //  * retrieves the input object associated with "$this" field
    //  * @return Input
    //  * @throws FlatbedException
    //  */
    // public function getInput()
    // {
    //     if (!$this->getUnformatted("input")) return null;

    //     $name = $this->getUnformatted("input");
    //     $input = $this->api("extensions")->get($name);

    //     if(!$input instanceof Input) throw new FlatbedException("Failed to retrieve Input ('$name'). Requested by $this ('$this->name'). Make sure this is a valid Input or that the Input is installed.");

    //     $input->field = $this;
    //     return $input;

    // }

    // protected function processSavePath()
    // {

    //     // handle new object creation
    //     if ($this->isNew()) {

    //         $this->path = $this->api("config")->paths->fields . $this->name . "/";
    //         if (!file_exists($this->path)) {
    //             mkdir($this->path, 0777, true);
    //         }
    //     }

    // }

    // public function get($name)
    // {
    //     switch ($name) {
    //         // case 'type':
    //         //     return $this->fieldtype;
    //         // case 'input':
    //         //     return $this->getInput();
    //         default:
    //             return parent::get($name);
    //     }
    // }

}