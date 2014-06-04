<?php

class ImageArray extends FileArray
{


    public function __construct($page){

        parent::__construct($page); // TODO:  add check for if already init

        $this->data = array_filter( $this->data, function($file){
            return $file instanceof Image;
        });

    }

}
