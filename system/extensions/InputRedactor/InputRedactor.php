<?php

class InputRedactor extends Input
{

    protected function setup(){

        app('config')->styles->add($this->url . "redactor/redactor.css");
        app('config')->styles->add($this->url . "{$this->name}.css");
        app('config')->scripts->add($this->url . "redactor/redactor.js");
        app('config')->scripts->add($this->url . "redactor/fullscreen.js");
        app('config')->scripts->add($this->url . "{$this->name}.js");

    }

    protected function renderInput()
    {

        $this->attribute('class', 'ui input ' . $this->className);
        $attributes = $this->getAttributes();
        $output = "<textarea $attributes >$this->value</textarea>";
        return $output;

    }

}