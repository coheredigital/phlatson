<?php

class MarkupFormtab extends Extension
{
    // array of field markup to be rendered

    public $id;
    public $fields = array();
    public $label;

    public function add($field)
    {
        $this->fields[] = $field;
        // create a unique id;
        $this->id = api("sanitizer")->name($this->label);

    }


    public function render($active = false)
    {

        $fields = "";
        $class = $active ? "active" : "";
        foreach ($this->fields as $field) {

            if (is_object($field)) {
                $fields .= $field->render();
            }
        }

        $output = "<div class='{$this->className} ui tab {$class}' data-tab='{$this->id}'>{$label}{$fields}{$submit}</div>";
        return $output;


    }



}