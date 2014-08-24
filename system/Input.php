<?php

abstract class Input extends Extension
{
    public $value;
    public $label;
    public $wrap = true;
    protected $fieldtype;
    protected $object;

    protected $attributes = [];
    protected $settings = [];

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
        return null;
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


    public function __toString()
    {
        return $this->render();
    }

}