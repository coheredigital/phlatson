<?php

class Field extends Object
{
    protected $dataFolder = "fields/";
    protected $attributes = null;

    /**
     * retrieves the filedtype object associated with "$this" field
     * @return Fieldtype
     */
    public function type()
    {
//        if ($this->data->fieldtype) {
        if ($this->data["fieldtype"]) {

//            $name = (string) $this->data->fieldtype;
            $name = (string) $this->data["fieldtype"];
            $fieldtype = new $name($this);

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

    public function get($string)
    {
        switch ($string) {
            case 'fieldtype':
            case 'type':
                return $this->type();
                break;
            case 'template':
                $template = new Template("field");
                return $template;
                break;
            default:
                return parent::get($string);
                break;
        }
    }


}