<?php
namespace Phlatson;
class InputTinymce extends Input
{

    protected function setup(){

        $this->api('config')->styles->add( $this->url . "{$this->name}.css" );
        $this->api('config')->scripts->add($this->url . "tinymce/tinymce.min.js");
        $this->api('config')->scripts->add($this->url . "{$this->name}.js");

    }

    protected function renderInput()
    {

        $this->attribute('class', 'ui input ' . $this->className);
        $output = "<textarea $this->attributes >{$this->value}</textarea>";
        return $output;

    }

}
