<?php

class FieldtypeObject extends FieldtypeSelect
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