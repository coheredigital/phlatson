<?php

namespace Phlatson;

class DataFolder
{
    protected App $app;
    protected string $path;
    protected array $subfolders;
    protected array $files;

    public function __construct(App $app, string $path)
    {
        $this->app = $app;
        $this->path = $app->path() . trim($path, '/') . '/';

        if (!\file_exists($this->path)) {
            throw new \Exception('Invalid path: ' . $this->path);
        }
    }

    public function folder(): string
    {
        return \str_replace($this->app->path(), '', $this->path);
    }

    public function subfolders(): array
    {
        if (isset($this->subfolders)) {
            return $this->subfolders;
        }

        $this->subfolders = [];

        $subfolders = glob($this->path . '*', GLOB_ONLYDIR | GLOB_NOSORT);

        if (!\count($subfolders)) {
            return $this->subfolders;
        }

        foreach ($subfolders as $subfolder) {
            $subfolder = \basename($subfolder);
            $this->subfolders[$subfolder] = new DataFolder($this->app, $this->folder() . $subfolder);
        }

        return $this->subfolders;
    }

    public function files(): array
    {
        // if (isset($this->files)) {
        // 	return $this->files;
        // }

        $this->files = [];

        $files = glob($this->path . '*.*', GLOB_NOSORT);

        if (!\count($files)) {
            return $this->files;
        }

        foreach ($files as $file) {
            $file = \basename($file);
            $this->files[$file] = new File($this->path . $file);
        }

        return $this->files;
    }
}
