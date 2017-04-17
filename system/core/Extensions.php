<?php

class Extensions extends Objects
{

    protected $rootFolder = "extensions";
    protected $singularName = "Extension";
    const SINGULAR_CLASSNAME = 'Extension';


    public function __construct()
    {
        parent::__construct();

        $systemExtensions = SYSTEM_PATH . "extensions" . DIRECTORY_SEPARATOR;

        $this->preloadFileList($systemExtensions);
        $this->preloadFileList();

        $this->initializeAutoloadExtensions();
    }


    /**
     * preload autoload extensions and ExtensionStubs
     */

    protected function initializeAutoloadExtensions(){
        foreach ($this->data as $className => $file) {

            $extension = new ExtensionStub($file);

            if( $extension->autoload ){
                $extension = $extension->instantiate();
            }

            $this->data[$className] = $extension;
        }

    }

    public function get(string $name): ?Object
    {

        // TODO double check need for this
        $extension = $this->initialize($name, $extension);

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
    protected function initialize($name, $extension)
    {
        if(!$extension instanceof Extension){
            $extension = new $name($extension->file);
        }
        return $extension;
    }

}
