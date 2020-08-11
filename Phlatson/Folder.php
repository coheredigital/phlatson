<?php

namespace Phlatson;

class Folder
{
    protected App $app;
    protected Folder $parent;
    protected string $name;
    protected string $path;
    protected string $uri;
    protected array $children;
    protected FolderCollection $folders;
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

    public function folder(): string
    {
        return $this->uri;
    }

    public function children(): array
    {
        if (!isset($this->children)) {
            $this->children = glob($this->path . '*', GLOB_NOSORT);
        }

        return $this->children;
    }

    public function subfolders(): FolderCollection
    {
        if (!isset($this->folders)) {
            $this->folders = new FolderCollection($this->app);
            $children = $this->children();
            foreach ($children as $path) {
                $basename = \basename($path);
                if (\is_file($path)) {
                    continue;
                }
                $folder = new Folder(
                    $this->app,
                    $this->uri . $basename,
                    $this
                );
                $this->folders->append($folder);
            }
        }

        return $this->folders;
    }

    public function parent(): ?Folder
    {
        return $this->parent ?? null;
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
        if (!isset($this->files)) {
            $this->files = new FileCollection($this->app);
            $children = $this->children();
            foreach ($children as $path) {
                $basename = \basename($path);

                if (!\is_file($path)) {
                    continue;
                }
                $file = new File($this->path . $basename);
                $this->files->append($file);
            }
        }

        return $this->files;
    }
}
