<?php

class Upload extends Core
{

    protected $page;
    protected $path;

    public function __construct($page){

        $this->page = $page;


        if($this->page->isNew()){
            $tempFolderName = md5($this->page->url);
            $this->path = api("config")->paths->assets . "uploads/{$tempFolderName}/";

            // create the temp upload folder
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }

        }
        else{
            $this->path = $this->page->path;
        }

    }



    public function send($files){

        if (!empty($files)) {

            $tempFile = $files['file']['tmp_name'];
            $file = $this->path . $files['file']['name'];
            move_uploaded_file( $tempFile , $file );

        }

    }








}