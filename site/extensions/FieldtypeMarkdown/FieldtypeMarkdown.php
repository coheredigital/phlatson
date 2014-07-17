<?php

class FieldtypeMarkdown extends FieldtypeTextarea
{


    protected function setup()
    {
        require_once "Parsedown.php";
        // 	api::get('config')->styles->add($this->url."epiceditor/themes/base/epiceditor.css");
        // 	api::get('config')->scripts->add($this->url."epiceditor/js/epiceditor.js");
        // 	api::get('config')->scripts->add($this->url."{$this->className}.js");
    }


    public function getOutput($value)
    {
        return Parsedown::instance()->parse($value);
    }

    public function getEdit($value)
    {
        return trim($value);
    }


}