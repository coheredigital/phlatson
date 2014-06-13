<?php

class FieldtypeFiles extends Fieldtype
{


    public function render()
    {


        $input = $this->renderInput();

        $output = "<div class='col col-{$this->columns}'>";
        $output .= "<div class='field-item'>";
        if ($this->label) {
            $output .= "<div class='field-heading'>";
            $output .= "<label for=''>";
            $output .= $this->label ? $this->label : $this->attribute("name");
            $output .= "</label>";
            $output .= "</div>";
        }
        $output .= "<div class='field-content'>";
        $output .= $input;
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;

    }



    protected function renderInput()
    {
        $attributes = $this->getAttributes();

        foreach ($this->value as $file){
            $output .= "<div class='file'>";
            if($file instanceof Image){
                $output .= "<img src='{$file->url}' height='100' width='100'>";
            }
            $output .= "{$file->name}</div>";



        }

        $output .= "<input {$attributes} type='file' name='{$this->name}' value='{$this->value}'>";
        return $output;
    }

}

