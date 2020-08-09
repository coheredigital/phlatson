<?php

namespace Phlatson;

class DataFolder
{
    protected App $app;
    protected DataFolder $parent;
    protected string $path;
    protected array $children;
    protected array $files;

    public function __construct(App $app, string $path, ?DataFolder $parent = null)
    {
        $this->app = $app;
        $this->path = $app->path() . trim($path, '/') . '/';

        if (!\file_exists($this->path)) {
            throw new \Exception('Invalid path: ' . $this->path);
        }

        if (isset($parent)) {
            $this->parent = $parent;
        }

        $this->files();
        $this->children();
    }

    public function folder(): string
    {
        return \str_replace($this->app->path(), '', $this->path);
    }

    public function children(): array
    {
        if (isset($this->children)) {
            return $this->children;
        }

        $this->children = [];

        $folders = glob($this->path . '*', GLOB_ONLYDIR | GLOB_NOSORT);

        if (!\count($folders)) {
            return $this->children;
        }

        foreach ($folders as $folder) {
            $folder = \basename($folder);
            $this->children[$folder] = new DataFolder($this->app, $this->folder() . $folder, $this);
        }

        return $this->children;
    }

    public function parent(): ?DataFolder
    {
        return $this->parent ?? null;
    }

    public function files(): array
    {
        if (isset($this->files)) {
            return $this->files;
        }

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
