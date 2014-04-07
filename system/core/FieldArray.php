<?php

class FieldArray extends ObjectArray
{

    protected function isValidItem($item){

        if($item instanceof Field){
            return true;
        }
        return false;

    }

}
