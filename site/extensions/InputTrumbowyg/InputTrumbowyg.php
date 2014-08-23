<?php

class InputTrumbowyg extends InputTextarea
{

    protected function renderInput()
    {

        $api = api();

        $this->attribute('class', 'ui input ' . $this->className);

        api('config')->styles->add($this->url . "trumbowyg/design/css/trumbowyg.css");
        api('config')->styles->add($this->url . "{$this->name}.css");

        api('config')->scripts->add($this->url . "trumbowyg/trumbowyg.min.js");
        api('config')->scripts->add($this->url . "{$this->name}.js");

        $attributes = $this->getAttributes();
        $output = "<textarea $attributes >$this->value</textarea>";
        return $output;

    }

}