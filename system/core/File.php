<?php

class File extends Core
{

    protected $data = array();

    public function __construct( $page , $name ){

        // TODO : throw exception if not valid file

        $this->path = $page->path;
        $this->url = api("config")->urls->pages . $page->directory . "/" . rawurlencode( $name );
        $this->file = $path . $name;
        $this->name = $name;

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
            case 'path':
                return $this->path;
                break;
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
