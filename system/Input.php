<?php

abstract class Input extends Extension
{

    protected $fieldtype;

    protected $attributes = array(
        "type" => "text"
    );

    public $value;
    public $label;

    final public function fieldtype(Fieldtype $fieldtype)
    {
        $this->fieldtype = $fieldtype;
    }


    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    final public function render()
    {
        $input = $this->renderInput();
        $output = $this->renderWrapper($input);
        return $output;
    }


    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    final protected function renderWrapper($input)
    {

        $output = "<div class='field field-{$this->name}'>";

        $output .= "<label for='{$this->name}'>";
        $output .= $this->label ? $this->label : $this->name;
        $output .= "</label>";

        $output .= $input;
        $output .= "</div>";

        return $output;
    }

    protected function renderInput()
    {
        $attributes = $this->getAttributes();
        $output = "<input {$attributes} type='text' value='$this->value'>";
        return $output;
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

            case 'directory':
                return normalizeDirectory($this->name);
            case 'url':
                return api('config')->urls->site . $this->rootFolder . "/" . $this->name . "/";
            case 'path':
            case 'name':
                return $this->attribute($name);
            case 'className':
                return get_class($this);
            default:
                return $this->getFormatted($name);
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

    public function __toString(){
        return $this->render();
    }

}