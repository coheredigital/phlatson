<?php


abstract class Extension extends Object
{

    protected $dataFolder = "extensions/";

    public function __construct(Field $field = null)
    {
        parent::__construct();

        $this->setup();
        $this->addStyles();
        $this->addScripts();
    }

    protected function setup()
    {
    }

    protected function addStyles()
    {
    }

    protected function addScripts()
    {
    }

    public function get($name)
    {
        switch ($name) {
            case 'name':
                return $this->className;
            default:
                return parent::get($name);
                break;
        }
    }

}