<?php


abstract class Extension extends Object
{

    protected $rootFolder = "extensions/";

    public function __construct()
    {
        $this->load(); // called manually because most object wont call load unless a URL paramter has been passed to __construct
        parent::__construct(null);


        $this->setup();

    }

    protected function setup()
    {
        $this->addStyles();
        $this->addScripts();
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
            case 'directory':
                return $this->get("className");
            case 'type':
                return "Extension";
            default:
                return parent::get($name);
                break;
        }
    }

}