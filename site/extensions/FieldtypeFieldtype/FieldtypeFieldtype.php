<?php

class FieldtypeFieldtype extends Fieldtype
{
    protected $page;
    protected $objectType = "field";

    public function getOutput($name)
    {
        $field = app("extensions")->get("$name");
        return $field;
    }

    public function getSave($value)
    {
        if ($value instanceof Fieldtype) {
            return $value->name;
        }
        else{
            $fieldtype = app("extensions")->get($value);
            if ($fieldtype instanceof Fieldtype) return $fieldtype->name;
        }
        return null;
    }

    public function options()
    {

        $fieldtypes = app("extensions")->all();
        $fieldtypes
            ->filter(["type"=>"Fieldtype"])
            ->sort("title");

        $options = [];
        foreach($fieldtypes as $fieldtype) {
            $options[$fieldtype->title] = $fieldtype->name;
        }

        return $options;
    }

}