<?php

class FieldtypeSelect extends Fieldtype
{

    protected $selectOptions = array();

    public function render()
    {


        $attributes = $this->getAttributes();
        $options = $this->getOptions();


        $output = "<div class='column $this->columns wide'>";
        $output .= "<div class='field'>";
        $output .= "<label for=''>";
        $output .= "{$this->label}";
        $output .= "</label>";
        $output .= "<select {$attributes} >{$options}</select>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;

    }


    protected function setup()
    {
        $options = $this->field->settings->options;
        if ($options) {
            $options = $this->field->settings->options->children();

            foreach ($options as $option) {
                $key = $option["value"];
                $this->selectOptions["$option"] = "$option";
            }
        }
    }

    public function setOptions(array $array)
    {
        $this->selectOptions = $array;
    }

    protected function getOptions()
    {
        $output = "";
        foreach ($this->selectOptions as $key => $value) {
            $selected = $this->value == $value ? "selected='selected'" : null;
            $output .= "<option {$selected} value='{$value}'>{$key}</option>";
        }
        return $output;
    }


}