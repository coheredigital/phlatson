<?php

abstract class Fieldtype extends Extension
{

    public $wrap = true;
    protected $fieldtype;
    protected $object;

    public $value;
    public $name;
    public $label;

    protected $attributes = [];
    protected $settings = [];

    protected function setup()
    {
        $this->attribute('class', 'input ' . $this->className);
        if ($field instanceof Field) {
            $this->field = $field;
        }
    }

    public function setObject(Object $object)
    {
        $this->object = $object;
    }

    /**
     * alias for the three available formatting methods,
     * allows passing of type, can auto determing required method
     * @param  mixed $name raw value from SimpleXML object
     * @param  string $type
     * @return mixed        determined by fieldtype object
     */
    final public function get($name, $type = null)
    {

        switch ($name) {
            case 'type':
                return "Fieldtype";
//            case 'value':
//                if ($this->object instanceof Object) {
//                    return $this->object->getUnformatted($this->field->name);
//                }
            default:
                switch ($type) {
                    case 'output':
                        return $this->getOutput($name);
                    case 'save':
                        return $this->getSave($name);
                }
                return parent::get($name);
        }
    }

    public function getOutput($value)
    {
        return (string)$value;
    }


    public function getSave($value)
    {
        return $value;
    }

    final public function setField(Field $field)
    {
        $this->field = $field;
    }



    protected function getAttributes()
    {
        $string = "";

        foreach ($this->attributes as $key => $value) {
            $string .= "{$key}='$value' ";
        }
        return trim($string);

    }


    public function attribute($key, $value = false)
    {
        if (!$value && $this->hasAttribute($key)) {
            return $this->attributes[$key];
        } else {
            if ($value) {
                $this->attributes[$key] = (string)$value;
                return $this;
            }
        }
        return false;
    }


    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }


    public function setting($key, $value = false)
    {
        if (!$value && $this->hasSetting($key)) {
            return $this->settings[$key];
        } else {
            if ($value) {
                $this->settings[$key] = $value;
                return $this;
            }
        }
        return false;

    }
    public function settings($array)
    {
        if (count($array)) {
            $this->settings = array_merge($this->settings, $array);
        }
        return $this;

    }

    public function hasSetting($key)
    {
        return isset($this->settings[$key]);
    }



    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    final protected function renderWrapper($input)
    {

        $output = "<div class='field field-{$this->name} {$this->name}'>";

        if ($this->label !== false) {
            $output .= "<label class='field-label' for='{$this->name}'>";
            $output .= $this->label ? $this->label : $this->name;
            $output .= "</label>";
        }

        $output .= "<div class='field-input' for='{$this->name}'>";
        if ($this->setting('required')) {
            $output .= "<div class='field-required''></div>";
        }
        $output .= "$input";
        $output .= "</div>";

        $output .= "</div>";

        return $output;
    }


    protected function renderInput()
    {
        return "";
    }


    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    final public function render()
    {
        $output = $this->renderInput();
        if ($this->wrap) {
            $output = $this->renderWrapper($output);
        }
        return $output;
    }


}