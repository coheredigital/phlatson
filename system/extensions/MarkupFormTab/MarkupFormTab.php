<?php
namespace Flatbed;
class MarkupFormTab extends Extension
{
    // array of field markup to be rendered

    public $id;
    public $inputs = array();
    public $label;

    public function add($field)
    {
        $this->inputs[] = $field;
        // create a unique id;
        $this->id = md5($this->label);
    }


    public function render()
    {

        $output = "";
        foreach ($this->inputs as $input) {
            if ($input instanceof Input) {
                $output .= $input->render();
            }
        }

        $output = "<li class='{$this->className}' data-tab='{$this->id}'>{$label}{$output}{$submit}</li>";
        return $output;


    }


}