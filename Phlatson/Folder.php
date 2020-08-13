<?php

namespace Phlatson;

class Folder
{
    protected App $app;
    protected DataFile $index;
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

        $this->index();

        if (!\file_exists($this->path)) {
            throw new \Exception('Invalid path: ' . $this->path);
        }
    }

    // TODO: remove this
    public function index()
    {
        $file = $this->path . 'index.json';
        if (!\file_exists($file)) {
            $this->updateIndex();
        }

        $this->index = new DataFile($file);

        return $this->index;
    }

    public function updateIndex()
    {
        $index = new DataFile(null, $this);
        $data = [
            'files' => [],
            'folders' => []
        ];
        $contents = glob($this->path . '*', GLOB_NOSORT);

        foreach ($contents as $path) {
            $type = \is_file($path) ? 'files' : 'folders';
            $data[$type][] = \basename($path);
        }

        $index->name = 'index';
        $index->set('files', $data['files']);
        $index->set('folders', $data['folders']);
        $index->set('modified', (int) date('U'));
        // return $this->index;
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

    public function child(string $name): ?Folder
    {
        $children = $this->children();

        if (!$children->has($name)) {
            return null;
        }

        return $this->children->get($name);
    }

    public function children(): FolderCollection
    {
        if (!isset($this->children)) {
            $this->children = new FolderCollection($this->app, $this);
        }

        foreach ($this->index->get('folders') as $basename) {
            $this->children->append($basename);
        }

        return $this->children;
    }

    public function file(string $name)
    {
        $files = $this->files();

        if (!$this->hasFile($name)) {
            return null;
        }

        if (!$files->has($name)) {
            return null;
        }

        return $files->get($name);
    }

    public function files(): FileCollection
    {
        if (!isset($this->files)) {
            $this->files = new FileCollection($this->app, $this);
        }

        foreach ($this->index->get('files') as $path) {
            $this->files->append($path);
        }

        return $this->files;
    }

    public function parent(): ?Folder
    {
        return $this->parent ?? null;
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

    public function hasFile(string $name): bool
    {
        // $files = $this->index->get('files');

        // return isset($files[$name]);
        return \file_exists($this->path . $name);
    }

    public function hasFiles(): bool
    {
        return count($this->contents('files')) > 0;
    }
}
