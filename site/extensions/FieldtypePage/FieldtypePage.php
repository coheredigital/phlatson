<?php

class FieldtypePage extends Fieldtype
{

    public function getOutput($url)
    {
        $page = app("pages")->get("$url");
        return $page;
    }

    public function getSave($value)
    {
        if ($value instanceof Page) {
            $value = $value->name;
        } else {
            $page = app("pages")->get("$url");
            $value = $page->url;
        }
        $value = "$value"; // stringify for certainty :)
        return $value;
    }


}