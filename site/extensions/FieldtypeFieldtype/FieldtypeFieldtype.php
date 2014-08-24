<?php

class FieldtypeFieldtype extends Fieldtype
{
    protected $page;
    protected $objectType = "field";

    public function getOutput($name)
    {
        $field = api("extensions")->get("$name");
        return $field;
    }

    public function getSave($value)
    {
        if ($value instanceof Fieldtype) {
            return $value->name;
        }
        return null;
    }

}