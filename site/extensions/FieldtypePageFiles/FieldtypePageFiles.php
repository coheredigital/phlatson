<?php

class FieldtypePageFiles extends Fieldtype
{

    protected function setup(){
        api::get("config")->scripts->add( $this->url . "dropzone.js" );
        api::get("config")->scripts->add( $this->url . $this->className . ".js" );
        api::get("config")->styles->add( $this->url . $this->className . ".css" );
    }

    public function render()
    {

        $input = $this->renderInput();

        $output = "<div class='column $this->columns wide'>";
        $output .= "<div class='field'>";
        $output .= $input;
        $output .= "</div>";
        $output .= "</div>";
        return $output;

    }

    protected function renderInput()
    {

        $output .= "<div class='ui divided list dropzone-previews' id='PageFilesList'>";

        foreach ($this->object->files as $file){
            $output .= "<div class='item'>";
            if($file instanceof Image){
                $output .= "<img  class='ui image' src='{$file->url}' height='32' width='32' data-dz-thumbnail>";
            }
            else{
                $output .= "<div class='FiletypeIcon FiletypeIcon-{$file->extension}'><div class='FiletypeIcon-label'>{$file->extension}</div></div>";
            }
            $output .= "<div class='right floated red ui icon button'><i class='trash icon'></i></div>";
            $output .= "<div class='content'>";
            $output .= "<div class='header'>{$file->name}</div>";
            $output .= "<div class='description'>{$file->filesizeFormatted}</div>";
            $output .= "</div>";
            $output .= "</div>";

        }
        $output .= "</div>";
        $output .= "<div action='./?name=" . api::get("input")->get->name . "' class='' id='files'></div>";
        $output .= "<div class='ui segment {$this->name}-dragndrop dz-clickable dropzone'>";
        $output .= "<span class='ui header'><i class='big cloud upload icon'></i> <span class='content'>Drag &amp; drop files here";
        $output .= '<div class="sub header">or click to choose files</div>';
        $output .= "</span>";
        $output .= "</span>";

        $output .= "</div>";
        return $output;
    }




}

