<?php

class FieldtypeFiles extends Fieldtype
{


    protected function setup(){

        api("config")->scripts->add( $this->url . "dropzone.js" );
        api("config")->scripts->add( $this->url . $this->className . ".js" );
//        api("config")->styles->add( $this->url . $this->className . ".css" );
        api("config")->styles->add( $this->url . "temp.css" );




    }


    public function render()
    {


        $input = $this->renderInput();

        $output = "<div class='column $this->columns wide'>";
        $output .= "<div class='field'>";
        if ($this->label) {
            $output .= "<label for=''>";
            $output .= $this->label ? $this->label : $this->attribute("name");
            $output .= "</label>";
        }
        $output .= $input;
        $output .= "</div>";
        $output .= "</div>";
        return $output;

    }



    protected function renderInput()
    {
//        $attributes = $this->getAttributes();

        $output .= "<div class='ui divided list'>";

        foreach ($this->value as $file){
            $output .= "<div class='item'>";
            if($file instanceof Image){
                $output .= "<img  class='ui image' src='{$file->url}' height='32' width='32'>";
            }
            $output .= "<div class='right floated tiny red ui button'>Remove</div>";
            $output .= "<div class='content'>";
            $output .= "<div class='header''>{$file->name}</div>";
            $output .= "</div>";
            $output .= "</div>";

        }

        $output .= "<div action='./' class='dropzone' id='files'></div>";

        $output .= "</div>";
        return $output;
    }




}

