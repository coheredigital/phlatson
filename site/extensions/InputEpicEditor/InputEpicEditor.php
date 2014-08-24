<?php

class InputEpicEditor extends Input
{


    protected function setup()
    {
         	api('config')->styles->add($this->url."epiceditor/themes/base/epiceditor.css");
         	api('config')->scripts->add($this->url."epiceditor/js/epiceditor.js");
         	api('config')->scripts->add($this->url."{$this->className}.js");
    }




}