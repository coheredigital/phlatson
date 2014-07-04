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
        $this->filesize = filesize($this->file);
        $this->filesizeFormatted = $this->formatSizeUnits($this->filesize);
        $this->name = $name;
        $this->ext = pathinfo($name, PATHINFO_EXTENSION);


    }

    protected function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 1) . ' GiB';
        }
        elseif ($bytes >= 104857)
        {
            $bytes = number_format($bytes / 1048576, 1) . ' MiB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 1) . ' KiB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
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
