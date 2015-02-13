<?php

class FieldtypeDateTime extends Fieldtype
{


    public function getOutput($value)
    {
//        $value = date((string)$this->settings->format, (int)$value);
//        return $value;
    }

    public function getSave($value)
    {
        $value = strtotime($value);
        return $value;
    }

    protected function renderInput()
    {

        registry('config')->styles->add($this->url . "/datetimepicker/jquery.datetimepicker.css");
        registry('config')->scripts->add($this->url . "/datetimepicker/jquery.datetimepicker.js");
        registry('config')->scripts->add($this->url . "/$this->className.js");

        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }


        $attributes = $this->getAttributes();
        $output = "<input {$attributes}>";
        return $output;
    }


}