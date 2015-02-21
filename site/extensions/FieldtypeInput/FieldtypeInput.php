<?php

class FieldtypeInput extends Fieldtype
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
        if ($value instanceof Input) {
            return $value->name;
        }
        else{
            $input = app("extensions")->get($value);
            if ($input instanceof Input) return $input->name;
        }
        return null;
    }

    public function options()
    {

        $inputs = app("extensions")->all();
        $inputs
            ->filter(["type"=>"Input"])
            ->sort("title");

        $options = [];
        foreach($inputs as $fieldtype) {
            $options[$fieldtype->title] = $fieldtype->name;
        }

        return $options;
    }

}