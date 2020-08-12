<?php

namespace Phlatson;

class File
{
    public string $file;
    public string $filename;
    public string $path;
    public string $name;
    public string $extension;
    public string $url;
    public string $uri;
    protected Folder $parent;
    protected int $modified;

    public function __construct(string $file, ?Folder $parent)
    {
        if (!\file_exists($file)) {
            throw new \Exception("File ($file) does not exist");
        }

        $pathinfo = \pathinfo($file);
        if (isset($parent)) {
            $this->parent = $parent;
        }

        $this->file = $file;
        $this->filename = $pathinfo['filename'];
        $this->path = $pathinfo['dirname'] . '/';
        $this->name = $pathinfo['basename'];
        $this->extension = $pathinfo['extension'];
        $this->modified = \filemtime($this->file);
    }

    public function path(): string
    {
        return  $this->path;
    }

    public function folder(): string
    {
        return \str_replace($this->parent->path(), '/', $this->path());
    }

    public function filesize(): int
    {
        return filesize($this->file);
    }

    public function filesizeStr(): int
    {
        $bytes = $this->filesize();

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 1) . ' GiB';
        } elseif ($bytes >= 104857) {
            return number_format($bytes / 1048576, 1) . ' MiB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KiB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif (1 == $bytes) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    public function rename(string $name): void
    {
    }
}
