<?php

class File extends Core
{

    protected $page;
    protected $data = array();

    public function __construct( $page , $name ){

        // TODO : throw exception if not valid file

        $this->page = $page;
        $this->path = $page->path;
        $this->url = api("config")->urls->pages . $page->directory . "/" . rawurlencode( $name );
        $this->file = $page->path . $name;
        $this->name = $name;
        $this->ext = pathinfo($name, PATHINFO_EXTENSION);


    }



    public function get($string)
    {
        switch ($string) {
            case 'directory':
                if(is_null($this->name)){
                    $lastRequestIndex = count($this->route) - 1;
                    $this->name = $this->route[$lastRequestIndex];
                }
                return $this->name;

            default:
                return $this->data[$string];
                break;
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value)
    {
        switch($name){
            default:
                $this->data[$name] = $value;
        }
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

}
