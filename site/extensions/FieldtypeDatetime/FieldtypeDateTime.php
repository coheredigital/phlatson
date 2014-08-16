<?php

class FieldtypeDateTime extends Fieldtype
{

    protected function setup()
    {
        api('config')->styles->add($this->url . "/datetimepicker/jquery.datetimepicker.css");
        api('config')->scripts->add($this->url . "/datetimepicker/jquery.datetimepicker.js");
        api('config')->scripts->add($this->url . "/$this->className.js");
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