<?php

class FieldtypeRichtext extends Fieldtype
{

    protected function renderInput()
    {

        $this->attribute('class', 'ui input ' . $this->className);

        api('config')->styles->add($this->url . "redactor/redactor.css");
        api('config')->styles->add($this->url . "{$this->name}.css");

        api('config')->scripts->add($this->url . "redactor/redactor.js");
        api('config')->scripts->add($this->url . "redactor/fullscreen.js");
        api('config')->scripts->add($this->url . "{$this->name}.js");

        $attributes = $this->getAttributes();
        $output = "<textarea $attributes >$this->value</textarea>";
        return $output;

    }

}