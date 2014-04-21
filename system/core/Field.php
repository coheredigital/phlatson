<?php

class Field extends Object
{
    protected $root = "fields/";
    protected $attributes = null;

    /**
     * retrieves the filedtype object associated with "$this" field
     * @return Fieldtype
     */
    public function type()
    {
        if ($this->getUnformatted("fieldtype")) {
            $name = (string) $this->getUnformatted("fieldtype");
            return new $name($this);
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

    public function get($string)
    {
        switch ($string) {
            case 'fieldtype':
            case 'type':
                return $this->type();
                break;
            case 'template':
                return new Template("field");
                break;
            default:
                return parent::get($string);
                break;
        }
    }


}