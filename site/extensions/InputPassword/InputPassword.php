<?php
/**
 * Created by PhpStorm.
 * User: aspruijt
 * Date: 22/08/14
 * Time: 12:48 PM
 */



class InputPassword extends Input{

    protected function renderInput()
    {
        if ($this->value){
            $this->attribute("value",$this->value);
        }
        if ($this->name){
            $this->attribute("name",$this->name);
        }
        $attributes = $this->getAttributes();
        $output = "<input {$attributes} type='password'>";
        return $output;
    }

} 