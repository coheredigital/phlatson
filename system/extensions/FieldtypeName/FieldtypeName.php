<?php

class FieldtypeName extends Fieldtype
{

    protected function getSave($value){

        $value =  api("sanitizer")->name($value);
        return $value;
    }

}