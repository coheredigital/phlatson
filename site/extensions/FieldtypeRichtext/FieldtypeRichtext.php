<?php

class FieldtypeRichtext extends Fieldtype
{

    protected function setup(){

        registry('events')->listen("FieldtypeRichtext.render", function(){
            registry('config')->styles->add($this->url . "redactor/redactor.css");
            registry('config')->styles->add($this->url . "{$this->name}.css");

            registry('config')->scripts->add($this->url . "redactor/redactor.js");
            registry('config')->scripts->add($this->url . "redactor/fullscreen.js");
            registry('config')->scripts->add($this->url . "{$this->name}.js");
        });
    }

    protected function renderInput()
    {



        $this->attribute('class', 'ui input ' . $this->className);


        $attributes = $this->getAttributes();
        $output = "<textarea $attributes >$this->value</textarea>";
        return $output;

    }

}