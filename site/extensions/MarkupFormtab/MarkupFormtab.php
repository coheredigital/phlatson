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


    public function render()
    {

        $fields = "";
        foreach ($this->fields as $field) {
            if (is_object($field)) {
                $fields .= $field->render();
            }
        }

        $output = "<li class='{$this->className} tab' data-tab='{$this->id}'>{$label}{$fields}{$submit}</li>";
        return $output;


    }


}