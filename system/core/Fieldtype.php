<?php

abstract class Fieldtype extends Extension
{
    protected $attributes = array();

    protected $field;
    public $label;
    public $value;

    // contains defaults settings and there defaults values
    // can be extended by other Fieldtypes
    protected $settings = array();

    final public function __construct(Field $field = null)
{


        $this->attribute('class', 'field-input ' . $this->className);
        if ($field instanceof Field) {
            $this->field = $field;
        }
         parent::__construct();
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
        switch ($type) {
            case 'output':
                return $this->getOutput($name);
                break;
            case 'raw':
            case 'edit':
                return $this->getEdit($name);
                break;
            case 'save':
                return $this->getSave($name);
                break;
            case 'type':
                return "Fieldtype";
        }

        switch ($name) {
            case 'type':
                return "Fieldtype";
            default:
                return parent::get($name);
                break;
        }
    }

    protected function getEdit($value)
    {
        return (string)$value;
    }

    protected function getOutput($value)
    {
        return (string)$value;
    }

    /**
     * getSave() should return type DomElement
     */
    public function getSave($value)
    {

        return $value;

    }


    public function setField(Field $field)
    {
        $this->field = $field;
    }

    public function getInput()
    {
        // $input =  $this->api("extensions")->get("InputtypeText");
        return $this;
    }

    // we will default to rendering a basic text field since it will be the most common inout type for most field types
    public function render()
    {


        $input = $this->renderInput();

        $output = "<div class='col col-{$this->columns}'>";
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
        $attributes = $this->getAttributes();
        $output = "<input {$attributes} type='text' name='{$this->name}' value='{$this->value}'>";
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