<?php

class Extensions extends Objects
{

    protected $rootFolder = "extensions";
    protected $singularName = "extension";

    public function __construct()
    {
        parent::__construct();

        $systemExtensions = SYSTEM_PATH . "extensions" . DIRECTORY_SEPARATOR;

        $this->preloadExtensionList($systemExtensions);
        $this->preloadExtensionList();

        $this->initializeAutoloadExtensions();
    }


    /**
     * preloads the available data directories / files into '$this->data' using getFileList()
     * @param  string $path the location to be searched
     */
    protected function preloadExtensionList($path = null)
    {
        foreach ($this->rootFolders as $folder) {
            $path = ROOT_PATH . $folder . $this->rootFolder . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
            $this->data += $this->getFileList($path);
        }
    }

    /**
     * scans the available data directories and returns the found array
     * key : basename of folder
     * value : path to data file
     * @param  string $path the location to be searched
     */
    protected function getFileList($path): array
    {
        if (!file_exists($path)) {
            throw new FlatbedException("Cannot get file list, invalid path: {$path}");
        }


    
        $folders = glob( $this->path . "*", GLOB_ONLYDIR | GLOB_NOSORT);

        $fileList = [];
        foreach ($folders as $folder) {
            $name = basename($folder);
            $fileList["$name"] = $folder . DIRECTORY_SEPARATOR . "data.json";
        }
        return $fileList;
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

    public function get(string $name)
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
