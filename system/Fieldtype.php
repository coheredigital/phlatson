<?php

abstract class Fieldtype extends Extension
{
    protected $attributes = array();

    protected $field;
    protected $value;

    final public function __construct($file)
    {
        $this->attribute('class', 'ui input ' . $this->className);
        if ($field instanceof Field) {
            $this->field = $field;
        }
        parent::__construct($file);
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
            case 'value':
                if($this->object instanceof Object){
                    return $this->object->getUnformatted($this->field->name);
                }
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
        return (string) $value;
    }


    public function getSave($value)
    {
        return $value;
    }

    final public function setField(Field $field)
    {
        $this->field = $field;
    }

    public function getInput()
    {
        // $input =  $this->api::get("extensions")->get("InputtypeText");
        return $this;
    }

    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    public function render()
    {


        $input = $this->renderInput();

        $output = "<div class='column wide'>";
        $output .= "<div class='field'>";
        if ($this->label) {

            $output .= "<label for=''>";
            $output .= $this->label ? $this->label : $this->attribute("name");
            $output .= "</label>";

        }

        $output .= $input;

        $output .= "</div>";
        $output .= "</div>";
        return $output;
    }

    protected function renderInput()
    {
        $value = $this->value;
        $attributes = $this->getAttributes();
        $output = "<input {$attributes} type='text' name='{$this->name}' value='{$value}'>";
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
        if (!$value && isset($this->attributes["$key"])) {
            return $this->attributes["$key"];
        } else {
            $this->attributes["{$key}"] = (string)$value;
        } // only string values accepted for attributes

    }


}