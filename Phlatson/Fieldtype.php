<?php
namespace Phlatson;
abstract class Fieldtype extends Extension
{

    protected $field;
    public $object;

    /**
     * format value for output
     *
     * @param mixed raw input value
     * @return mixed formatted publc facing variable
     */
    public function input($value)
    {
        return $value;
    }

    public function output($value)
    {
        return $value;
    }

}