<?php

namespace Phlatson;

class Page extends DataObject
{
    const BASE_FOLDER = 'pages/';
    const BASE_URL = '';

    protected $parent;
    protected $parents;
    protected $children;
    protected array $files = [];

    public function rootFolder(): string
    {
        return str_replace($this->url(), '', $this->folder()) . '/';
    }

    public function url(): string
    {
        // remove root from path
        $value = \str_replace($this->rootPath(), '', $this->path());
        $value = trim($value, '/');
        $value = $value ? "/$value/" : '/';

        return $value;
    }

    public function parent(): ?Page
    {
        $rootPath = $this->rootPath();
        $parentPath = dirname($this->path()) . '/';

        // check if root is in parent path
        if (false === strpos($parentPath, $rootPath)) {
            return null;
        }

        $url = '/' . str_replace($rootPath, '', $parentPath);
        $url = rtrim($url, '/') . '/';

        $page = new Page($url, $this->finder);
        // $page->setData($this->finder->getDataFor("Page", $url));

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
        $this->parents = new ObjectCollection($this->finder);

        $current = $this;

        while (null !== $current->parent()) {
            $this->parents->append($current->parent());
            $current = $current->parent();
        }

        // cache result
        $this->parents->reverse();

        return $this->parents;
    }

    public function children(): ObjectCollection
    {
        $url = $this->url;
        $children = $this->children;

        // skip if already stored
        if ($children instanceof ObjectCollection) {
            return $children;
        }

        // create empty collection
        $children = new ObjectCollection($this->finder);

        $folders = $this->subfolders();
        foreach ($folders as $folder) {
            $name = basename($folder);
            $children->append($this->child($name));
        }

        $this->children = $children;

        return $children;
    }

    public function subfolders(): array
    {
        $path = $this->path() . '/*';

        return glob($path, GLOB_ONLYDIR | GLOB_NOSORT);
    }

    public function child(string $name): Page
    {
        $name = trim($name, '/');
        $path = $this->url() . $name;

        return new Page($path, $this->finder);
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
}
