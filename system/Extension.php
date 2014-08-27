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

}