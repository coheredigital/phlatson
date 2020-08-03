<?php

namespace Phlatson;

class File
{
    public string $file;
    public string $path;
    public string $name;
    public string $extension;
    public string $url;
    protected int $modified;
    protected DataFolder $folder;

    public function __construct(string $file)
    {

        if (!file_exists($file)) {
            throw new \Exception("File ($file) does not exist");
        }

        // TODO : throw Exception if not valid file
        $this->file = $file;
        $this->path = dirname($this->file) . '/';
        $this->name = \basename($file);
        $this->extension = pathinfo($file, PATHINFO_EXTENSION);
        $this->modified = \filemtime($this->file);

    }

    public function path(): string
    {
        return $this->path;
    }

    public function folder(): string
    {
        return $this->path;
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
