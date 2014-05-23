<?php


abstract class Extension extends Object
{

    protected $root = "extensions/";

    public function __construct()
    {
        $this->load(); // called manually because most object wont call load unless a URL paramter has been passed to __construct
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