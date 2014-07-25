<?php

class FieldtypeName extends Fieldtype
{

    public function getSave($value){

        $value =  api("sanitizer")->name($value);
        return $value;
    }

}