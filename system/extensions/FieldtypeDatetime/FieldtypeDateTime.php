<?php
namespace Flatbed;
class FieldtypeDateTime extends Fieldtype
{

    public function output($value)
    {
        
        if(is_int($value)){
            $value = date("c", $value);
        }
        
        $datetime = new FlatbedDateTime($value);

        return $datetime;
    }

    public function input($value)
    {
        if(is_int($value)){
            $value = date("c", $value);
        }
        $datetime = new FlatbedDateTime($value);
        $value = (int) $datetime->format("U");
        return $value;
    }


}