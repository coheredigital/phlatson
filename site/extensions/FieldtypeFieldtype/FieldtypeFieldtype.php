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

    protected function renderInput()
    {

        $fieldtypes = app("extensions")->all();
        $fieldtypes
            ->filter(["type"=>"Fieldtype"])
            ->sort("title");

        foreach($fieldtypes as $fieldtype) {
            if ($fieldtype->name == $this->value->name) $selected = "selected";
            else $selected = "";
            $options .= "<option $selected value='$fieldtype->name'>$fieldtype->title</option>";
        }

        $attributes = $this->getAttributes();
        $output = "<select {$attributes}>$options</select>";
        return $output;
    }

}