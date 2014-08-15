<?php

class FieldtypeRedactor extends FieldtypeTextarea
{

    protected function setup()
    {

        $this->attribute('class', 'ui input ' . $this->className);

        api('config')->styles->add($this->url . "redactor/redactor.css");
        api('config')->styles->add($this->url . "{$this->className}.css");

        api('config')->scripts->add($this->url . "redactor/redactor.js");
        api('config')->scripts->add($this->url . "{$this->className}.js");
    }

}