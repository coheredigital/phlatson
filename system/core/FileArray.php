<?php

class FileArray extends ObjectArray
{

    protected $page;

    public function __construct($page){

        $this->page = $page;

        $fileList = $this->getFileList();

        if($fileList){

            foreach($this->getFileList() as $file){

                $file = $page->path . $file;

                if( !is_file($file ) ) return false;

                if(!getimagesize( )){
                    $fileObject = new File( $this->page , $file );
                }
                else{
                    $fileObject = new Image( $this->page , $file );
                }

                if($fileObject){
                    $this->add($fileObject);
                }

            }

        }



    }


    protected function getFileList(){

        if( $this->page->isNew() ) return false;

        $files = scandir($this->page->path);
        // filter non files
        $files = array_filter($files, function($item){
            return !is_dir( $this->page->path . $item);
        });

        //filter JSON files
        $files = array_filter($files, function($item){
            $mime = pathinfo ($this->page->path . $item);
            return $mime["extension"] != "json";
        });

        return $files;
    }


}
