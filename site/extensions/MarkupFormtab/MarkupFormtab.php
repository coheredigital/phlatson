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
        $this->id = app("sanitizer")->name($this->label);
    }


    public function render()
    {

        $fields = "";
        foreach ($this->fields as $fieldtype) {
            if (is_object($fieldtype)) {
                $fields .= $fieldtype->render();
            }
        }

        $output = "<li class='{$this->className} tab' data-tab='{$this->id}'>{$label}{$fields}{$submit}</li>";
        return $output;


    }


}