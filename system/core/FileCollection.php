<?php

class FileCollection extends ObjectCollection
{

    protected $page;

    public function __construct($page)
    {

        $this->page = $page;

        $fileList = $this->getFiles();

        if ($fileList) {

            foreach ($this->getFiles() as $filename) {

                $file = $page->path . $filename;

                if (!is_file($file)) {
                    return false;
                }

                if (!getimagesize($file)) {
                    $fileObject = new File($this->page, $filename);
                } else {
                    $fileObject = new Image($this->page, $filename);
                }

                if ($fileObject) {
                    $this->add($fileObject);
                }

            }

        }


    }


    protected function getFiles()
    {

        if ($this->page->isNew()) {
            return false;
        }

        $files = scandir($this->page->path);
        // filter non files
        $files = array_filter(
            $files,
            function ($item) {
                return !is_dir($this->page->path . $item);
            }
        );

        //filter JSON files
        $files = array_filter(
            $files,
            function ($item) {
                $mime = pathinfo($this->page->path . $item);
                return $mime["extension"] != "json";
            }
        );

        return $files;
    }


}
