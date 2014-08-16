<?php

class FieldtypePage extends FieldtypeText
{

    public function getOutput($url)
    {
        $page = api("pages")->get("$url");
        return $page;
    }

    public function getSave($value)
    {
        if ($value instanceof Page) {
            $value = $value->name;
        } else {
            $page = api("pages")->get("$url");
            $value = $page->url;
        }
        $value = "$value"; // stringify for certainty :)
        return $value;
    }


}