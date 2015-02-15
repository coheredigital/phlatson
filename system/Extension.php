<?php


class Extension extends Object
{

    protected $info; // like a data array in a regular object but holds the default info for the module

    protected $rootFolder = "extensions";


    final public function __construct($file = null)
    {

        // forced file location
        // TODO: this is require because of the way router handles Class.method route callbacks
        // File is not being passed so not enough can be determined about the extension (path/file)
        $file = app("config")->paths->extensions . $this->className . '/' . static::DEFAULT_SAVE_FILE;

        parent::__construct($file);



//        if ($this->autoload === true) {
            $this->setup();
//        }
        $this->setupListeners();
    }

    final protected function setupListeners(){
        $listeners = $this->listeners;
    }

    protected function setup()
    {
    }

}