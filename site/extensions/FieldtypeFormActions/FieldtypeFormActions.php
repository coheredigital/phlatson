<?php

class FieldtypeFormActions extends Fieldtype
{

    public $object;

    public function render()
    {

        api("config")->styles->append("{$this->url}{$this->name}.css");

        $output = "<div class='container'>";
        $output .= "<div class='$this->className'>";
        $output .= "<button type='submit' class='button'><i class='icon icon-save'></i> Save </button> ";
        $output .= "<button type='submit' class='button'> <i class='icon icon-times'></i> Delete </button> ";
        $output .= "<a href='{$this->dataObject->url}' target='_external' class='button'><i class='icon icon-share'></i> View</a>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;

    }


}