<?php

class Upload extends Core
{

    protected $page;
    protected $tempPath;
    protected $destinationPath;

    public function __construct($page){

        $this->page = $page;

        $tempFolderName = md5($this->page->url);
        $this->tempPath = api("config")->paths->assets . "uploads/{$tempFolderName}/";

        if (!file_exists($this->tempPath)) {
            mkdir($this->tempPath, 0777, true);
        }

        $this->destinationPath = $page->path;

    }



    public function commit(){
        move_uploaded_file( $tempFile , $file );
    }

    public function send($files){

        if (!empty($files)) {

            $tempFile = $files['file']['tmp_name'];
            $file = $this->tempPath . $files['file']['name'];
            move_uploaded_file( $tempFile , $file );

        }

    }








}