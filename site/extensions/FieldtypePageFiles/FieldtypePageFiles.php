<?php

class FieldtypePageFiles extends Fieldtype
{

    protected function setup()
    {
        api("config")->scripts->add($this->url . "dropzone.js");
        api("config")->scripts->add($this->url . $this->className . ".js");
        api("config")->styles->add($this->url . $this->className . ".css");
    }

    protected function renderInput()
    {
        $config = api("config");

        $output .= "<div class='file-list dropzone-previews' id='PageFilesList'>";

        foreach ($this->object->files as $file) {
            $output .= "<div class='item'>";
            if ($file instanceof Image) {
                $output .= "<img  class='thumbnail' src='{$file->url}' height='64' width='64' data-dz-thumbnail>";
            } else {
                $output .= "<div class='FiletypeIcon FiletypeIcon-{$file->extension}'><div class='FiletypeIcon-label'>{$file->extension}</div></div>";
            }
//            $output .= "<div class='right floated red ui icon button'><i class='trash icon'></i></div>";
            $output .= "<div class='content'>";
            $output .= "<div class='header'>{$file->name}</div>";
            $output .= "<div class='description'>{$file->filesizeFormatted}</div>";
            $output .= "</div>";
            $output .= "</div>";

        }
        $output .= "</div>";
        $output .= "<div action='{$config->urls->admin}pages/upload/{$this->object->directory}' class='' id='files'></div>";
        $output .= "<div class='{$this->name}-dragndrop dz-clickable dropzone'>";
        $output .= "<i class='big cloud upload icon'></i>Drag &amp; drop files here";
        $output .= '<div class="sub header">or click to choose files</div>';
        $output .= "</div>";
        return $output;
    }


}

