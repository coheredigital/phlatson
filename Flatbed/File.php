<?php
namespace Flatbed;
class File extends Object
{

    public function __construct(Page $page, $name)
    {

        // TODO : throw FlatbedException if not valid file

        $this->page = $page->url;
        $this->path = $page->path;
        $this->url = $this->api("pages")->url . $page->uri . "/" . rawurlencode($name);
        $this->file = $page->path . $name;
        $this->filesize = filesize($this->file);
        $this->filesizeFormatted = $this->formatSizeUnits($this->filesize);
        $this->name = $name;
        $this->extension = pathinfo($name, PATHINFO_EXTENSION);


    }

    protected function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 1) . ' GiB';
        } elseif ($bytes >= 104857) {
            $bytes = number_format($bytes / 1048576, 1) . ' MiB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 1) . ' KiB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }


    public function get( string $string)
    {
        switch ($string) {
            case 'directory':
                if (is_null($this->name)) {
                    $lastRequestIndex = count($this->route) - 1;
                    $this->name = $this->route[$lastRequestIndex];
                }
                return $this->name;

            default:
                return $this->data[$string];
                break;
        }
    }




}
