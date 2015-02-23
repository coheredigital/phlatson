<?php


class Extensions extends Objects
{

    protected $rootFolder = "extensions";
    protected $singularName = "extension";


//    protected $info = [];

    public function __construct()
    {
        parent::__construct();

        $systemExtensions = __DIR__ . DIRECTORY_SEPARATOR . "extensions" . DIRECTORY_SEPARATOR;
        $this->getFileList($systemExtensions);
        $this->getFileList(); // for now this need to be fired on every request TODO: remove this requirement

    }

    protected function getFileList($path = null, $depth = 1)
    {

        if(is_null($path)) $path = $this->rootPath;

        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        $iterator->setMaxDepth($depth);

        foreach ($iterator as $item) {

            $itemPath = Filter::path($item->getPath());
            $itemFile = $item->getFileName();

            $filePath = $itemPath . $itemFile;

            if (!$this->isValidObject($item)) continue;

            $className = $this->getNameFromPath($itemPath);

            $extensionData = json_decode(file_get_contents($filePath));
            // add data file path for lazy instantiation
            $extensionData->file = $filePath;


            $this->data["$className"] = $extensionData;

            if($extensionData->autoload){
                $extension = new $className($filePath);
                $this->data["$className"] = $extension;
            }

        }

    }

    protected function getNameFromPath($path){

        $path = trim($path, "/");

        $className = substr($path, strrpos($path, '/') + 1);
        $className = Filter::uri($className);
        return $className;
    }

    protected function getObject($key)
    {
        // get the file if it exists
        if (!$extension = $this->getItem($key)) {
            return false;
        }

        if(!$extension instanceof Extension){
            $extension = new $key($extension->file);
            $this->data["$key"] = $extension;
        }

        if(!$extension->singluar){
            $extension = clone $extension; // TODO I don't know if I want to use clone here
        }

        return $extension;
    }


    public function all()
    {
        $this->getObjectList();
        $collection = new ObjectCollection();

        foreach ($this->data as $object) {
            $collection->add($object);
        }

        return $collection;
    }


}