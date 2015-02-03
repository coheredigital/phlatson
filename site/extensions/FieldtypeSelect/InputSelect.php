<?php
/**
 * Created by PhpStorm.
 * User: aspruijt
 * Date: 22/08/14
 * Time: 12:48 PM
 */

class FieldtypeSelect extends Input{

    public $options = [];


    protected function getOptions()
    {

        $this->value = $this->fieldtype->value ? $this->fieldtype->value : $this->value;

        $output = "";
        foreach ($this->options as $value => $text) {
            $selected = $this->value == $value ? "selected='selected'" : null;
            $output .= "<option $selected value='$value'>$text</option>";
        }
        return $output;
    }


    protected function renderInput()
    {
        $attributes = $this->getAttributes();
        $options = $this->getOptions();
        $output = "<select {$attributes}>$options</select>";
        return $output;
    }

} 