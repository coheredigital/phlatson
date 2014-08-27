<?php

class FieldtypeDateTime extends Fieldtype
{


    public function getOutput($value)
    {
        $value = date((string)$this->settings->format, (int)$value);
        return $value;
    }

    public function getSave($value)
    {
        $value = strtotime($value);
        return $value;
    }

    protected function renderInput()
    {

        api('config')->styles->add($this->url . "/datetimepicker/jquery.datetimepicker.css");
        api('config')->scripts->add($this->url . "/datetimepicker/jquery.datetimepicker.js");
        api('config')->scripts->add($this->url . "/$this->className.js");

        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }

        if ($this->name) {
            $this->attribute("name", $this->name);
        }
        $attributes = $this->getAttributes();
        $output = "<input {$attributes}>";
        return $output;
    }


}