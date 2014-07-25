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
            $fieldtype = api("extensions")->get($name);

            if( $fieldtype instanceof Fieldtype ) {
                $fieldtype->setField($this);
                return  $fieldtype;
            }

        }
        return null;
    }

    protected function getNewName()
    {
        // set object name
        if ( $this->template->settings->nameFrom  && $this->template->fields->has( $this->settings->nameFrom ) ) { // TODO : this is not in yet, we need support for creating the name from referencing another field
            return api("sanitizer")->name( $this->settings->nameFrom );
        }
        else {
            return api("sanitizer")->name( $this->label );
        }

    }


    protected function processSavePath(){

        // handle new object creation
        if($this->isNew()){

            $this->path = api("config")->paths->fields . $this->name . "/";
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }

    }

    /**
     * get/set field attributes
     * @param  string $key
     * @param  mixed $value optional
     * @return mixed
     */
    public function attributes($key, $value = null)
    {
        if (isset($value)) {
            $this->attributes["$key"] = $value;
        } else {
            return $this->attributes["$key"];
        }
    }

    public function get($name)
    {
        switch ($name) {

            case 'fieldtype':
            case 'type':
                return $this->getType();
                break;
            case 'template':
                return api("templates")->get("field");
                break;
            default:
                return parent::get($name);
                break;
        }
    }


}