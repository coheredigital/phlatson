<?php


class Extensions
{

    protected $path;
    protected $data;

    public function __construct()
    {
        $this->path = api("config")->paths->extensions;
        $this->data = $this->getList();
    }

    public function all()
    {
        return $this->data;
    }

    protected function getList()
    {
        $extensionList = [];

        $iterator = new RecursiveDirectoryIterator( $this->path, RecursiveDirectoryIterator::SKIP_DOTS );
        $iterator = new RecursiveIteratorIterator( $iterator, RecursiveIteratorIterator::SELF_FIRST );

        $iterator->setMaxDepth(1);

        foreach ($iterator as $item) {

            $file = $item->getPathName();
            $file = normalizePath($file);

            $itemFilename = $item->getFileName();

            if( $itemFilename != "data.json" ) continue;

            $extensionName = str_replace($this->path, "", $file);
            $extensionName = str_replace($itemFilename,"",$extensionName);
            $extensionName = trim($extensionName,DIRECTORY_SEPARATOR);
            $extensionName = normalizeDirectory($extensionName);

            // add root items for pages to allow home selection

            $extension = new $extensionName($file);

            $extensionList["$extensionName"] = $extension;

        }

        return $extensionList;

    }

    public function get($name)
    {
        switch ($name){
            default:
                $key = normalizeDirectory($name);
                if ( isset($this->data[$key]) ) return $this->data[$key];
        }
    }

}