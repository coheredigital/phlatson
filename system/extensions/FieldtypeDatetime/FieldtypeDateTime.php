<?php

class FieldtypeDateTime extends Fieldtype
{

    public function get($value)
    {
        
        if(is_int($value)){
            $value = date("c", $value);
        }
        
        $datetime = new FlatbedDateTime($value);

        return $datetime;
    }

    public function set($value)
    {
        if(is_int($value)){
            $value = date("c", $value);
        }
        $datetime = new FlatbedDateTime($value);
        $value = (int) $datetime->format("U");
        return $value;
    }


}