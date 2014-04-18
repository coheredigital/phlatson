<?php


abstract class Extension extends Object
{

    protected $root = "extensions/";

    public function __construct(Field $field = null)
    {
        parent::__construct($field);

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

    public function get($string)
    {
        switch ($string) {
            case 'name':
                return $this->className;
            default:
                return parent::get($string);
                break;
        }
    }

}