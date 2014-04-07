<?php

class FieldtypeSelectPage extends Fieldtype
{

    protected $selectOptions = array();

    public function render()
    {


        $attributes = $this->getAttributes();
        $options = $this->getOptions();


        $output = "<div class='col col-{$this->columns}'>";
        $output .= "<div class='field-item'>";
        $output .= "<div class='field-heading'>";
        $output .= "<label for=''>";
        $output .= "{$this->label}";
        $output .= "</label>";
        $output .= "</div>";
        $output .= "<div class='field-content'>";
        $output .= "<select {$attributes} >{$options}</select>";
        $output .= "</div>";
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
        $home = api("pages")->get("/");
        foreach ($home->children as $p) {
            $selected = $this->value == $value ? "selected='selected'" : null;

            $output .= "<option {$selected} value='{$p->directory}'>{$p->title} | {$p->directory}</option>";
        }
        return $output;
    }


}