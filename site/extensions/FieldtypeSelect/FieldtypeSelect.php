<?php

class FieldtypeSelect extends Fieldtype
{

    protected $selectOptions = array();

    protected function setup()
    {

        $this->attribute("class", $this->attribute("class") . " FieldtypeSelect");

        $options = $this->field->settings->options;
        if ($options) {
            $options = $this->field->settings->options->children();

            foreach ($options as $option) {
                $key = $option["value"];
                $this->selectOptions["$option"] = "$option";
            }
        }

    }

    public function renderInput()
    {

        $input = new InputSelect();
        $input->options = [];

        $output = $input->render();
        return $output;

    }

    public function setOptions($array)
    {
        $this->selectOptions = $array;
    }

    protected function getOptions()
    {

        $output = "";

        if ($this->selectOptions instanceof ObjectArray) {
            foreach ($this->selectOptions as $object) {
                $selected = $this->value == $object->name ? "selected='selected'" : null;
                $output .= "<option {$selected} value='{$value->name}'>{$object->name}</option>";
            }
            return $output;
        } else {

            foreach ($this->selectOptions as $key => $value) {
                $selected = $this->value == $value ? "selected='selected'" : null;
                $output .= "<option {$selected} value='{$value}'>{$key}</option>";
            }
        }

        return $output;
    }


}