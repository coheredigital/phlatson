<?php

abstract class Input extends Extension
{
    public $value;
    public $label;
    public $wrap = true;
    protected $fieldtype;
    protected $object;

    protected $attributes = array(
        "type" => "text"
    );

    final public function fieldtype(Fieldtype $fieldtype)
    {
        $this->fieldtype = $fieldtype;
    }

    final public function setObject($object)
    {
        $this->object = $object;
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
        if (!$value && isset($this->attributes[$key])) {
            return $this->attributes[$key];
        } else {
            $this->attributes[$key] = (string)$value;
        }

    }


    public function get($name)
    {
        switch ($name) {
            case 'name':
                return $this->attribute($name);
            default:
                return parent::get($name);
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value)
    {
        switch ($name) {
            case "name":
                $this->attribute($name, $value);
                break;
        }
        return $this;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }


    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    final protected function renderWrapper($input)
    {

        $output = "<div class='field field-{$this->name}'>";

        $output .= "<label class='field-label' for='{$this->name}'>";
        $output .= $this->label ? $this->label : $this->name;
        $output .= "</label>";

        $output .= "<div class='field-input' for='{$this->name}'>{$input}</div>";

        $output .= "</div>";

        return $output;
    }

    protected function renderInput()
    {
        $attributes = $this->getAttributes();
        $output = "<input {$attributes} type='text' value='$this->value'>";
        return $output;
    }


    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    final public function render()
    {
        $output = $this->renderInput();
        if($this->wrap){
            $output = $this->renderWrapper($output);
        }
        return $output;
    }


    public function __toString(){
        return $this->render();
    }

}