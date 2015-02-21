<?php

class Field extends Object
{
    protected $rootFolder = "fields";
    protected $attributes = null;
    protected $requiredElements = ["fieldtype","input"];

    /**
     * retrieves the input object associated with "$this" field
     * @return Input
     * @throws Exception
     */
    public function getInput()
    {
        if (!$this->getUnformatted("input")) return null;

        $name = $this->getUnformatted("input");
        $input = app("extensions")->get($name);

        if(!$input instanceof Input) throw new Exception("Failed to retrieve Input ('$name'). Requested by $this ('$this->name'). Make sure this is a valid Input or that the Input is installed.");

        $input->field = $this;
        return $input;

    }

    protected function getNewName()
    {
        // TODO : this is not in yet, we need support for creating the name from referencing another field
        if ($this->template->_settings->nameFrom && $this->template->fields->has($this->settings->nameFrom)) {
            return app("sanitizer")->name($this->settings->nameFrom);
        } else {
            return app("sanitizer")->name($this->label);
        }

    }

    /**
     * Get raw fieldtype name without applied formatting
     * @return String
     */
    public function getFieldtypeName()
    {
        return $this->getUnformatted("fieldtype");
    }

    protected function processSavePath()
    {

        // handle new object creation
        if ($this->isNew()) {

            $this->path = app("config")->paths->fields . $this->name . "/";
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }

    }

    public function get($name)
    {
        switch ($name) {
            case 'type':
                return $this->fieldtype;
            case 'input':
                return $this->getInput();
            case 'template':
                $template = app("templates")->get("field"); //  TODO : refactor - the method for defining the master to this template is done manually here, maybe I can automate this like with pages
                $template->master = $this;
                return $template;
            default:
                return parent::get($name);
        }
    }

}