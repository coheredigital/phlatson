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

        if(is_null($path)) $path = $this->siteRoot;

        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        $iterator->setMaxDepth($depth);

        foreach ($iterator as $item) {

            $itemPath = Filter::path($item->getPath());
            $itemFile = $item->getFileName();

            $filePath = $itemPath . $itemFile;

            if (!$this->isValidObject($item)) continue;

            $className = $this->getNameFromPath($itemPath);

            $extension = new ObjectStub($filePath);
            $extension->creator = $this;

            $this->data["$className"] = $extension;

            if($extension->autoload){
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

    protected function getObject($name)
    {
        // get the file if it exists
        if (!$extension = $this->getItem($name)) {
            return false;
        }

        $extension = $this->instantiateExtension($name, $extension);


        if(!$extension->singluar){
            $extension = clone $extension; // TODO I don't know if I want to use clone here
        }

        return $extension;
    }

    protected function instantiateExtension($name, $extension){
        if(!$extension instanceof Extension){
            $extension = new $name($extension->file);
        }
        return $extension;
    }

    public function all()
    {
        $this->getObjectList();
        $collection = new ObjectCollection();

        foreach ($this->data as $name => $extension) {
            // todo: all extensions are being instatiate, all the time, this need to improved, maybe a ExtensionStub class?
//            $extension = $this->instantiateExtension($name, $extension);
            $collection->add($extension);
        }

        return $collection;
    }


}