<?php

class FieldtypeSelectFieldtype extends FieldtypeSelect
{


    public function getOutput($name)
    {
        $template = api::get("fields")->get("$name");
        return $template;
    }

    public function getSave($value)
    {
        if ( $value instanceof Field) {
            $value = $value->name;
        }
        $value = "$value"; // stringify for certainty :)
        return $value;
    }


    protected function setup()
    {
        $options = array();

        $fieldtypes = api::get("extensions")->fieldtypes;
        if (!$fieldtypes) return false;
        foreach ( $fieldtypes as $fieldtype ) {

            $title = str_replace("Fieldtype", "", $fieldtype->title);
            $options["$title"] = $fieldtype->name;
        }
        $this->selectOptions = $options;
    }

}