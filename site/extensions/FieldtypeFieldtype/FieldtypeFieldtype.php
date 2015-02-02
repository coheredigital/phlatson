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
        return null;
    }

    protected function renderInput()
    {

        $fieldtypes = app("extensions")->all();
        $fieldtypes
            ->filter(["type"=>"Fieldtype"])
            ->sort("title");

        foreach($fieldtypes as $fieldtype) {
            $options .= "<option value='$fieldtype->name'>$fieldtype->title</option>";
        }

        $attributes = $this->getAttributes();
        $output = "<select {$attributes}>$options</select>";
        return $output;
    }

}