<?php
namespace Flatbed;

class FieldtypeText extends Fieldtype
{
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
