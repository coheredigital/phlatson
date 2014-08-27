<?php

class FieldtypeField extends Fieldtype
{
    protected $page;
    protected $objectType = "field";


    protected function setup()
    {
        $this->label = "Field";
        $this->attribute("name", "template");
    }


    public function getOutput($name)
    {
        $field = api("fields")->get("$name");
        return $field;
    }

    public function getSave($value)
    {
        if ($value instanceof Field) {
            $value = $value->name;
        }
        $value = "$value"; // stringify for certainty :)
        return $value;
    }



}