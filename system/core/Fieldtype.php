<?php

abstract class Fieldtype extends Extension
{

    protected $field;
    protected $object;

    /**
     * format value for output
     *
     * @param mixed raw input value
     * @return mixed formatted publc facing variable
     */
    public function get($value)
    {
        return $value;
    }

    public function set($value)
    {
        return $value;
    }

}