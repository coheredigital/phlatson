<?php

abstract class Fieldtype extends Extension
{

    public $wrap = true;
    protected $field;
    protected $object;

    public $value = null;
    public $name;
    public $label;

    protected $attributes = [];

    protected function setup()
    {
        $this->attribute('class', 'input ' . $this->className);
        $this->attribute("name", $this->field->name);
        if ($field instanceof Field) {
            $this->field = $field;
        }
    }

    public function setObject(Object $object)
    {
        $this->object = $object;
    }



    public function getOutput($value)
    {
        return (string)$value;
    }

    public function getEdit($value)
    {
        return $this->getOutput($value);
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


    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    final protected function renderWrapper($input)
    {

        $columns = $this->settings->columns ? $this->settings->columns : 12;

        $output = "<div class='field field-{$this->name} {$this->name} column column-{$columns}'>";

        if ($this->label !== false) {
            $output .= "<label class='field-label' for='{$this->name}'>";
            $output .= $this->label ? $this->label : $this->name;
            $output .= "</label>";
        }

        $output .= "<div class='field-input' for='{$this->name}'>";
//        if ($this->setting('required')) {
//            $output .= "<div class='field-required''></div>";
//        }
        $output .= "$input";
        $output .= "</div>";

        $output .= "</div>";

        return $output;
    }


    protected function renderInput()
    {
        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }

        $attributes = $this->getAttributes();
        $output = "<input {$attributes}>";
        return $output;
    }



    // we will default to rendering a basic text field since it will be the most common input type for most field types
    final public function render()
    {
        $output = $this->renderInput();
        $output = $this->renderWrapper($output);

        return $output;
    }



}