<?php

namespace Phlatson;

class File
{
    protected string $file;
    protected string $name;
    protected string $extension;
    protected string $path;
    protected string $url;

    public function __construct(string $file, ?Page $page = null)
    {
        // TODO : throw Exception if not valid file
        $this->file = $file;
        $this->name = \basename($file);
        $this->extension = pathinfo($file, PATHINFO_EXTENSION);

        // page dependant parameters
        if (isset($page)) {
            $this->url = $page->url.$page->uri.'/'.rawurlencode($name);
            $this->page = $page->url;
            $this->path = $page->path;
        }
    }

    public function filesize(): int
    {
        return filesize($this->file);
    }

    public function filesizeStr(): int
    {
        $bytes = $this->filesize();

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 1).' GiB';
        } elseif ($bytes >= 104857) {
            return number_format($bytes / 1048576, 1).' MiB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KiB';
        } elseif ($bytes > 1) {
            return $bytes.' bytes';
        } elseif (1 == $bytes) {
            return $bytes.' byte';
        } else {
            return '0 bytes';
        }
    }

    public function rename(string $name): void
    {
    }
}
