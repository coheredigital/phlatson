<?php

class Field extends Object
{
    protected $rootFolder = "fields/";
    protected $attributes = null;

    protected $defaultFields = array(
        "label",
        "fieldtype"
    );

    /**
     * retrieves the filedtype object associated with "$this" field
     * @return Fieldtype
     */
    public function type()
    {
        if ($this->getUnformatted("fieldtype")) {
            $name = (string) $this->getUnformatted("fieldtype");
            $fieldtype = api("extensions")->get($name);
            return $fieldtype;
        }
        return null;
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
                return $this->type();
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