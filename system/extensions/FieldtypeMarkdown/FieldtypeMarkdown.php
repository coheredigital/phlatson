<?php

class FieldtypeMarkdown extends Fieldtype
{

    protected function setup()
    {
        require_once "Parsedown.php";
    }

    public function output($value)
    {
        return Parsedown::instance()->parse($value);
    }

    public function set($value)
    {
        return trim($value);
    }


}