<?php

class FieldtypeFormActions extends Fieldtype
{

    public $dataObject;

    protected function addStyles()
    {
        api('config')->styles->add($this->url . "{$this->className}.css");
    }

    public function render()
    {
        $output = "<div class='row clearfix'>";
        $output .= "<div class='col col-12'>";
        $output .= "<div class='field field-{$this->className}'>";

        $output .= "<button type='submit' class='ui green labeled icon button'> Save <i class='icon save'></i> </button> ";
        $output .= "<button type='submit' class='ui red labeled icon button'> Delete <i class='icon trash'></i></button> ";
        $output .= "<button type='submit' class='ui button'><i class='icon icon-copy'></i></button>";
        $output .= "<a href='{$this->dataObject->url}' target='_external' class='ui button'><i class='icon icon-external-link'></i></a>";

        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;

    }


}