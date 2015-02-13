<?php

class FieldtypeName extends Fieldtype
{

    public function getSave($value)
    {
        $value = registry("sanitizer")->name($value);
        return $value;
    }

}