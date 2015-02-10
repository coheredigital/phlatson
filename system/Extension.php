<?php


class Extension extends Object
{

    protected $info; // like a data array in a regular object but holds the default info for the module

    protected $rootFolder = "extensions";
    protected $defaultFields = [];

    final public function __construct($file = null)
    {
        parent::__construct($file);

        if ($this->autoload === true) {
            $this->setup();
        }
        $this->setupListeners();
    }

    final protected function setupListeners(){
        $listeners = $this->listeners;
    }

    protected function setup()
    {
    }

}