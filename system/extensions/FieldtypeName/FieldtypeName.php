<?php

class FieldtypeName extends Fieldtype
{

    public function getSave($value)
    {
        $value = app("sanitizer")->name($value);
        return $value;
    }

}