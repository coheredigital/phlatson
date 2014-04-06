<?php

class FieldtypeObject extends Fieldtype
{

    protected $objectType = null;

    protected function getOutput($value)
    {
        $object = new $this->objectType("$value");
        return $object;
    }

    protected function getEdit($value)
    {
        return (string)$value;
    }


}