<?php


class InputSelect extends Input implements ReceivesOptions
{

    protected $options = [];
    protected $selected = [];


    protected function setup(){

        $this->api('config')->styles->add($this->url . "selectize/css/selectize.css");
        $this->api('config')->styles->add($this->url . "{$this->className}.css");

        $this->api('config')->scripts->add($this->url . "selectize/js/standalone/selectize.js");
        $this->api('config')->scripts->add($this->url . "{$this->className}.js");

    }

    protected function renderOptions()
    {

        $output = "";
        foreach ($this->options as $value => $text) {
            $selected = $this->value == $value ? "selected='selected'" : null;
            $output .= "<option $selected value='$value'>$text</option>";
        }
        return $output;
    }


    /**
     * @param $name
     * @param $value
     * @param bool $selected
     *
     * Add options for select input
     */
    public function addOption($name, $value, $selected = false)
    {

        $this->options[$value] = $name;
        if ($selected) $this->selected[$value] = $name;
    }


    /**
     * @param $array
     *
     * Add an array of options for select input
     */
    public function addOptions($array)
    {

        foreach ($array as $name => $value) {
            $this->addOption($name, $value);
        }
    }

    protected function renderInput()
    {
        $options = $this->renderOptions();
        $this->attribute('class', 'ui input ' . $this->className);
        $attributes = $this->getAttributes();
        $output = "<select {$attributes} >$options</select>";
        return $output;
    }

} 