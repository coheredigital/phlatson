<?php

namespace Phlatson;

class Folder
{
    protected App $app;
    protected Folder $parent;
    protected string $name;
    protected string $path;
    protected string $uri;
    protected array $contents;
    protected FolderCollection $children;
    protected FileCollection $files;

    public function __construct(App $app, string $path, ?Folder $parent = null)
    {
        $this->app = $app;

        if (isset($parent)) {
            $this->parent = $parent;
        }

        $this->name = \basename($path);

        $this->uri = isset($this->parent) ? $this->parent->folder() : '/';
        $this->uri .= $this->name . '/';

        $this->path = $app->path() . \ltrim($this->uri, '/');

        if (!\file_exists($this->path)) {
            throw new \Exception('Invalid path: ' . $this->path);
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function folder(): string
    {
        return $this->uri;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function init(): void
    {
        $contents = glob($this->path . '*', GLOB_NOSORT);
        foreach ($contents as $path) {
            $type = \is_file($path) ? 'files' : 'folders';
            $basename = \basename($path);
            $this->contents[$type][$basename] = $basename;
        }
    }

    public function contents(?string $type = null): array
    {
        if (!isset($this->contents)) {
            $this->init();
        }

        return $type === null ? $this->contents : $this->contents[$type];
    }

    public function children(): FolderCollection
    {
        if (!isset($this->children)) {
            $this->children = new FolderCollection($this->app, $this);
        }

        if (count($this->contents('folders')) !== $this->children->count()) {
            $files = $this->contents('folders');
            foreach ($files as $basename) {
                $this->children->append($basename);
            }
        }

        return $this->children;
    }

    public function parent(): ?Folder
    {
        return $this->parent ?? null;
    }

    // public function hasChild(string $name): bool
    // {
    //     $path = $this->path . \ltrim($name, '/');

    //     return \file_exists($path);
    // }

    public function child(string $name): ?Folder
    {
        $children = $this->children();

        if (!$children->has($name)) {
            return null;
        }

        return $this->children->get($name);
    }

    public function find(string $uri): ?Folder
    {
        $uri = \trim($uri, '/');

        $parts = \explode('/', $uri);

        $folder = $this;

        foreach ($parts as $name) {
            $parent = $folder;
            if (!$parent->children()->has($name)) {
                return null;
            }
            $folder = $parent->children()->get($name);
        }

        return $folder;
    }

    public function rootParent(): Folder
    {
        $parent = $this;
        while ($parent->parent()) {
            $parent = $parent->parent();
        }

        return $parent;
    }

    public function isRoot(): bool
    {
        return $this->parent() === null;
    }

    public function files(): FileCollection
    {
        if (!isset($this->files) || count($this->contents('folders')) !== $this->children->count()) {
            $this->files = new FileCollection($this->app);
            foreach ($this->contents('files') as $path) {
                $basename = \basename($path);

                $file = $this->file($basename);
                $this->files->append($file);
            }
        }

        return $this->files;
    }

    public function file(string $name): ?File
    {
        if (!isset($this->files)) {
            $this->files = new FileCollection($this->app);
        }

        if (!$this->hasFile($name)) {
            return null;
        }

        $info = \pathinfo($name);

        switch ($info['extension']) {
            case 'json':
                $file = new DataFile($this->path . $name, $this);
                break;

            default:
                $file = new File($this->path . $name, $this);
                break;
        }

        $this->files->append($file);

        return $file;
    }

    public function hasFile(string $name): bool
    {
        return \file_exists($this->path . $name);
    }

    public function hasFiles(): bool
    {
        return count($this->contents('files')) > 0;
    }
}
