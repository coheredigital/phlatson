<?php

class FieldtypeName extends Fieldtype
{

    public function getSave($value){

        $value =  api::get("sanitizer")->name($value);
        return $value;
    }

}