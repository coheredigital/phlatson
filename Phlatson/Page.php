<?php

namespace Phlatson;

class Page extends DataObject
{
    public const BASE_FOLDER = 'pages/';
    public const BASE_URL = '';

    protected $parent;
    protected $parents;
    protected $children;
    protected array $files = [];

    public function rootFolder(): string
    {
        return $this->folder->parent()->folder();
    }

    public function url(): string
    {
        $rootFolder = $this->rootFolder();
        $folder = $this->folder();

        $url = \str_replace($rootFolder, '', $folder);
        $url = \trim($url, '/');

        return "/$url/";
    }

    public function parent(): ?Page
    {
        $rootPath = $this->rootPath();
        $parentPath = \dirname($this->path()) . '/';

        // check if root is in parent path
        if (false === strpos($parentPath, $rootPath)) {
            return null;
        }

        $url = '/' . str_replace($rootPath, '', $parentPath);
        $url = rtrim($url, '/') . '/';

        $page = $this->app->getPage($url);

        if ($page->exists()) {
            return $page;
        }

        return null;
    }

    public function parents(): ObjectCollection
    {
        // skip if already stored
        if (isset($this->parents) && $this->parents instanceof ObjectCollection) {
            return $this->parents;
        }

        // create empty collection
        $this->parents = new ObjectCollection($this->app);

        $current = $this;

        while ($current->parent() !== null) {
            $this->parents->append($current->parent());
            $current = $current->parent();
        }

        // cache result
        $this->parents->reverse();

        return $this->parents;
    }

    public function rootParent(): Folder
    {
        $parent = $this;
        while ($parent->parent()) {
            $parent = $parent->parent();
        }

        return $parent;
    }

    public function children(): ObjectCollection
    {
        // skip if already stored
        if ($this->children instanceof ObjectCollection) {
            return $this->children;
        }

        // create empty collection
        $this->children = new ObjectCollection($this->app);

        $folders = $this->subfolders();
        foreach ($folders as $folder) {
            $name = basename($folder);
            $this->children->append($this->child($name));
        }

        return $this->children;
    }

    public function subfolders(): array
    {
        $path = $this->path() . '/*';

        return glob($path, GLOB_ONLYDIR | GLOB_NOSORT);
    }

    public function child(string $name): Page
    {
        $name = trim($name, '/');
        $url = trim($this->url(), '/');
        $path = $url . $name;

        return $this->app->getPage($path);
    }

    public function files(): array
    {
        if (!$this->files) {
            $files = new \FilesystemIterator($this->path(), \FilesystemIterator::SKIP_DOTS);
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $file = $file->getPathname();
                    $this->files[] = new File($file);
                }
            }
        }

        return $this->files;
    }

    // statuses, placeholder pseduo code for now
    public function isPublished(): bool
    {
        return $this->dataFolder->has('published.json');
    }
}
