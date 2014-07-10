<?php

class FieldtypeDateTime extends Fieldtype
{

    protected function setup()
    {
        api::get('config')->styles->add($this->url . "/datetimepicker/datetimepicker.css");
        api::get('config')->scripts->add($this->url . "/datetimepicker/datetimepicker.js");
        api::get('config')->scripts->add($this->url . "/$this->className.js");
    }

    public function getOutput($value)
    {
        $value = date((string)$this->field->format, (int)$value);
        return $value;
    }



    public function getSave($value)
    {
        $value = strtotime($value);
        return $value;
    }


}