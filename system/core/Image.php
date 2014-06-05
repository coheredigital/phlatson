<?php

class Image extends File
{

    public function __construct( $page , $name ){
        parent::__construct( $page , $name );

        $this->setImageSize();

    }

    public function setImageSize(){
        $size = getimagesize($this->file);
        $this->width = $size[0];
        $this->height = $size[1];
    }

    public function resize($width, $height, $options = null){

        $saveFolder = api("config")->paths->cache . $this->page->directory;
        $saveName = rtrim($this->name, $this->ext) . "{$width}x{$height}." . $this->ext;

        $image = imagecreatefromjpeg($this->file);
        $image_resized = imagecreatetruecolor($width, $height);
        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

        // create directory if not exist

        if (!file_exists( $saveFolder )) {
            mkdir( $saveFolder , 0777, true);
        }

        imagejpeg($image_resized, $saveFolder . DIRECTORY_SEPARATOR . $saveName );
    }

    // gets an image based on a predefined size and set of parameters
    public function getSize($name){

    }

}