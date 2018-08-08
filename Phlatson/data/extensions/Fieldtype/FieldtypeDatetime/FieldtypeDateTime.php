<?php
namespace Phlatson;
class FieldtypeDateTime extends Fieldtype
{

    public function output($value)
    {
        
        if(is_int($value)){
            $value = date("c", $value);
        }
        
        $datetime = new PhlatsonDateTime($value);

        return $datetime;
    }

    public function input($value)
    {
        if(is_int($value)){
            $value = date("c", $value);
        }
        $datetime = new PhlatsonDateTime($value);
        $value = (int) $datetime->format("U");
        return $value;
    }


}