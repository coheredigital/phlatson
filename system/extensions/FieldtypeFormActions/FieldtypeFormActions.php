<?php

class FieldtypeFormActions extends Fieldtype
{

    public $object;

    public function render()
    {
        $output = "<div class='row clearfix'>";
        $output .= "<div class='column twelve wide'>";
        $output .= "<div class='field field-{$this->className}'>";

        $output .= "<button type='submit' class='ui green icon button'><i class='icon save'></i> </button> ";
        $output .= "<button type='submit' class='ui red icon button'> <i class='icon trash'></i></button> ";
        $output .= "<div class='ui icon buttons'>";
            $output .= "<a href='{$this->dataObject->url}' target='_external' class='ui button black'><i class='icon share'></i></a>";
        $output .= "</div>";

        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;

    }


}