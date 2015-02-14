<?php


class Extensions extends Objects
{

    protected $rootFolder = "extensions";
    protected $singularName = "extension";

    protected $info = [];

    public function __construct()
    {
        parent::__construct();
        $this->getFileList(); // for now this need to be fired on every request TODO: remove this requirement

    }


    protected function getFileList($depth = 1)
    {

        $iterator = new RecursiveDirectoryIterator($this->rootPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        $iterator->setMaxDepth($depth);

        foreach ($iterator as $item) {

            $itemPath = normalizePath($item->getPath());
            $itemFile = $item->getFileName();

            $filePath = $itemPath . $itemFile;

            if (!$this->isValidObject($item)) continue;

            $className = normalizeDirectory(str_replace($this->rootPath, "", $itemPath));

            // add root items for pages to allow home selection
            $extension = new $className($filePath);
            $this->data["$className"] = $extension;

        }

    }


    protected function getObject($key)
    {
        // get the file if it exists
        if (!$file =  $this->getItem($key)) {
            return false;
        }
        return  new $key($file);
    }


    public function all()
    {
        $this->getObjectList();
        $objectArray = new ObjectCollection();

        foreach ($this->data as $object) {
            $objectArray->add($object);
        }

        return $objectArray;
    }


}