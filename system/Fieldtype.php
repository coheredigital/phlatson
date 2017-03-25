<?php

abstract class Fieldtype extends Extension
{

    protected $field;
    protected $object;

    public function getOutput($value)
    {
        return $value;
    }

    public function getEdit($value)
    {
        return $this->getOutput($value);
    }

    public function getSave($value)
    {
        return $value;
    }

}