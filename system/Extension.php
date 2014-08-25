<?php


class Extension extends Object
{

    protected $info; // like a data array in a regular object but holds the default info for the module

    protected $rootFolder = "extensions";

    final public function __construct($file = null)
    {
        parent::__construct($file);

        $this->name = get_class($this);

        if ($this->autoload === true) {
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
            default:
                return parent::get($name);
                break;
        }
    }


}