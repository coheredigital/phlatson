<?php

class Upload
{

    protected $object;
    protected $path;

    public function __construct($object)
    {

        $this->object = $object;

        if ($this->object->isNew()) {
            $tempFolderName = md5($this->object->url);
            $this->path = api("config")->paths->assets . "uploads/{$tempFolderName}/";

            // create the temp upload folder
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }

        } else {
            $this->path = $this->object->path;
        }

    }


    public function send($files)
    {
        $tempFile = $files['file']['tmp_name'];
        $file = $this->path . $files['file']['name'];
        move_uploaded_file($tempFile, $file);
    }


}