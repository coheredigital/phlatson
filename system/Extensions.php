<?php


class Extensions extends Objects
{

    protected $rootFolder = "extensions";
    protected $singularName = "extension";


    public function __construct()
    {
        parent::__construct();

        $systemExtensions = __DIR__ . DIRECTORY_SEPARATOR . "extensions" . DIRECTORY_SEPARATOR;

        $this->getFileList($systemExtensions);
        $this->getFileList();

        $this->preloadExtensions();
    }


    /**
     * preload autoload extensions and ExtensionStubs
     * @return [type] [description]
     */
    protected function preloadExtensions(){
        foreach ($this->data as $className => $path) {
            $extension = new ObjectStub($path);

            if($extension->autoload){
                $extension = new $className();
            }
            $extension->file = $path;
            $this->data[$className] = $extension;
        }

    }

    protected function getObject($name)
    {
        // get the file if it exists
        if (!$extension = $this->getItem($name)) {
            return false;
        }

        // TODO double check need for this
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
        $extensions = new ObjectCollection();

        foreach ($this->data as $name => $file) {
            $extension = $this->getObject($name);
            $extensions->add($extension);
        }

        return $extensions;
    }


}