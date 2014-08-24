<?php

class FieldtypeMarkdown extends Fieldtype
{

    protected function setup()
    {
        require_once "Parsedown.php";
    }


    public function getOutput($value)
    {
        return Parsedown::instance()->parse($value);
    }

    public function getEdit($value)
    {
        return trim($value);
    }


}