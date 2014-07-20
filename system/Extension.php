<?php


abstract class Extension extends Object
{

    protected $rootFolder = "extensions";

    final public function __construct($file)
    {
        parent::__construct($file);
        $this->name = get_class($this);
        if ( $this->autoload === true ){
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