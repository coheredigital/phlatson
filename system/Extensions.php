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

    public function get($name)
    {

        // TODO double check need for this
        $extension = $this->init($name, $extension);

        if(!$extension->singluar){
            $extension = clone $extension; // TODO I don't know if I want to use clone here
        }

        return $extension;
    }

    /**
     * run extension init process
     * @param  string       $name           ClassName that the extension runs
     * @param  Exstension   $extension      Extension object that was retrieved
     * @return Extension                    returns same extension once init has run
     */
    protected function init($name, $extension)
    {
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

            $extension = $this->get($name);
            if (!$extension ) continue;
       
            $extensions->add($extension);
            
        }

        return $extensions;
    }


}