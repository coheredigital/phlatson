<?php

class Field extends Object
{
    protected $rootFolder = "fields";
    protected $attributes = null;

    /**
     * retrieves the filedtype object associated with "$this" field
     * @return Fieldtype
     */
    public function getType()
    {
        if ($this->getUnformatted("fieldtype")) {
            $name = $this->getUnformatted("fieldtype");
            $fieldtype = app("extensions")->get($name);

            if ($fieldtype instanceof Fieldtype) {
                $fieldtype->setField($this);
                return $fieldtype;
            }

        }
        return null;
    }

    /**
     * retrieves the input object associated with "$this" field
     * @return Input
     */
    public function getInput()
    {
        if ($this->getUnformatted("input")) {

            $name = $this->getUnformatted("input");
            $input = app("extensions")->get($name);
            $input->settings($this->_settings);
            return $input;


        }
        return null;
    }

    protected function getNewName()
    {
        // set object name
        if ($this->template->_settings->nameFrom && $this->template->fields->has(
                $this->_settings->nameFrom
            )
        ) { // TODO : this is not in yet, we need support for creating the name from referencing another field
            return app("sanitizer")->name($this->_settings->nameFrom);
        } else {
            return app("sanitizer")->name($this->label);
        }

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

            case 'fieldtype':
            case 'type':
                return $this->getType();
                break;
            case 'input':
                return $this->getInput();
                break;
            case 'template':
                return app("templates")->get("field");
                break;
            default:
                return parent::get($name);
                break;
        }
    }

}