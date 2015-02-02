<?php

class FieldtypeRichtext extends Fieldtype
{

    protected function renderInput()
    {

        $this->attribute('class', 'ui input ' . $this->className);

        app('config')->styles->add($this->url . "redactor/redactor.css");
        app('config')->styles->add($this->url . "{$this->name}.css");

        app('config')->scripts->add($this->url . "redactor/redactor.js");
        app('config')->scripts->add($this->url . "redactor/fullscreen.js");
        app('config')->scripts->add($this->url . "{$this->name}.js");

        $attributes = $this->getAttributes();
        $output = "<textarea $attributes >$this->value</textarea>";
        return $output;

    }

}