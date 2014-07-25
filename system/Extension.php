<?php


abstract class Extension extends Object
{

    protected $info; // like a data array in a regular object but holds the default info for the module

    protected $rootFolder = "extensions";

    final public function __construct($file)
    {
        parent::__construct($file);

        $this->name = get_class($this);

        // TODO : temp replace data.json with info.json, switch to using $path to get Objects
        $infoFile = str_replace("data.json","info.json",$this->file);
        $this->info = json_decode(file_get_contents($infoFile));

        if ( $this->info->autoload === true ){
            $this->setup();
        }

    }

    protected function setup()
    {
    }
    public function init() //  TODO temp workaround remove
    {
        $this->setup();
    }

    public function get($name)
    {
        switch ($name) {
            case 'directory':
                return $this->name;
            case 'type':
                return "Extension";
            default:
                return parent::get($name);
                break;
        }
    }


}