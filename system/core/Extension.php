<?php


abstract class Extension extends Object
{

    protected $root = "extensions/";

    public function __construct()
    {
        parent::__construct(null);

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
            case 'directory':
                return $this->get("className");
            default:
                return parent::get($string);
                break;
        }
    }

}