<?php

class FieldtypeObject extends FieldtypeSelect
{

    protected $objectType = null;

    public function getOutput($value)
    {
        $object = new $this->objectType("$value");
        return $object;
    }

    protected function getEdit($value)
    {
        return (string)$value;
    }


}