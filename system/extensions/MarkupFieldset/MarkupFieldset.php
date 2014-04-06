<?php

class MarkupFieldset extends Extension
{
    // array of field markup to be rendered

    public $fields = array();
    public $label;
    private $tabs;

    public function add(\Fieldtype $field)
    {
        $this->fields[] = $field;
    }


    public function render()
    {
        $colCount = 0;
        $rowOpen = false;
        $fields = "";

        foreach ($this->fields as $field) {

            if ($colCount == 0) {
                $fields .= "<div class='row'>"; // open new row div
                $rowOpen = true;
            }


            if (is_object($field)) {

                $colCount += $field->columns;
                $fields .= $field->render();

                if ($colCount == 12) {
                    $fields .= "</div>"; // close row div
                    $colCount = 0; // reset colCount
                    $rowOpen = false;
                }
            }
        }
        if ($rowOpen) {
            $fields .= "</div>"; // close row div
            $colCount = 0; // reset colCount
            $rowOpen = false;
        }

        if ($this->label) {
            $label = "<legend class='row'>{$this->label}</legend>";
        }

        $output = "<fieldset>{$label}{$fields}{$submit}</fieldset>";
        return $output;


    }


}