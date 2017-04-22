<?php
namespace Flatbed;
class FieldtypeRichtext extends Fieldtype
{

    protected function setup(){


        $this->api('config')->styles->add($this->url . "redactor/redactor.css");
        $this->api('config')->styles->add($this->url . "{$this->name}.css");
        $this->api('config')->scripts->add($this->url . "redactor/redactor.js");
        $this->api('config')->scripts->add($this->url . "redactor/fullscreen.js");
        $this->api('config')->scripts->add($this->url . "{$this->name}.js");

    }

    protected function renderInput()
    {



        $this->attribute('class', 'ui input ' . $this->className);


        $attributes = $this->getAttributes();
        $output = "<textarea $attributes >$this->value</textarea>";
        return $output;

    }

}