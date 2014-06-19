<?php

class FieldtypeParent extends FieldtypeText
{

    protected function renderInput()
    {
        $attributes = $this->getAttributes();
        $output = "<input {$attributes} disabled='disabled' type='text' name='{$this->name}' value='{$this->value}'>";
        return $output;
    }

}