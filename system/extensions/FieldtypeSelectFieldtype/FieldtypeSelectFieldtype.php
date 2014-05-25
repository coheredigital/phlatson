<?php

class FieldtypeSelectFieldtype extends FieldtypeSelect
{


    protected function setup()
    {
        $options = array();

        $fieldtypes = api("extensions")->fieldtypes;
        if (!$fieldtypes) return false;
        foreach ( $fieldtypes as $fieldtype ) {

            $title = str_replace("Fieldtype", "", $fieldtype->title);
            $options["$title"] = $fieldtype->name;
        }
        $this->selectOptions = $options;
    }

}