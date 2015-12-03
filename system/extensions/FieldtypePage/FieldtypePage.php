<?php

class FieldtypePage extends Fieldtype
{

    public function getOutput($url)
    {
        $page = $this->api("pages")->get("$url");
        return $page;
    }

    public function getSave($value)
    {
        if ($value instanceof Page) {
            $value = $value->url;
        } else {
            $page = $this->api("pages")->get("$url");
            $value = $page->url;
        }
        $value = "$value"; // stringify for certainty :)
        return $value;
    }



    protected function renderInput()
    {
        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value->url);
        }

        if ($this->name) {
            $this->attribute("name", $this->name);
        }
        $attributes = $this->getAttributes();
        $output = "<input {$attributes}>";
        return $output;
    }

}