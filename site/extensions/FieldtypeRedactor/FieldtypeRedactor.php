<?php

class FieldtypeRedactor extends FieldtypeTextarea
{

    protected function setup()
    {
        api::get('config')->styles->add($this->url . "redactor/redactor.css");
        api::get('config')->styles->add($this->url . "{$this->className}.css");

        api::get('config')->scripts->add($this->url . "redactor/redactor.js");
        api::get('config')->scripts->add($this->url . "{$this->className}.js");
    }

}