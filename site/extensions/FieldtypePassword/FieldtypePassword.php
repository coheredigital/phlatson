<?php

class FieldtypePassword extends Fieldtype
{

    protected function renderInput()
    {

        $this->attribute("type", "password");
        if ($this->value){
            $this->attribute("value",$this->value);
        }
        if ($this->name){
            $this->attribute("name",$this->name);
        }
        $attributes = $this->getAttributes();
        $output = "<input {$attributes}>";
        return $output;
    }

}