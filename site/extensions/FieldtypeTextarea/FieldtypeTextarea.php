<?php

class FieldtypeTextarea extends Fieldtype
{


    protected function renderInput()
    {
        $attributes = $this->getAttributes();
        $output = "<textarea {$attributes} name='{$this->name}' id='{$id}' cols='30' rows='10'>{$this->value}</textarea>";
        return $output;
    }

}