<?php

class FieldtypeRedactor extends FieldtypeTextarea
{

    protected function setup()
    {
        api('config')->styles->add($this->url . "redactor/redactor.css");
        api('config')->scripts->add($this->url . "redactor/redactor.js");
        api('config')->scripts->add($this->url . "{$this->className}.js");
    }

}