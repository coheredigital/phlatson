<?php

namespace Phlatson;

class Page extends DataObject
{
    const BASE_FOLDER = 'pages/';
    const BASE_URL = '';

    protected $parent;
    protected $children;
    protected $parents;
    protected array $files = [];

    public function rootFolder(): string
    {
        return str_replace($this->url(), '', $this->folder()) . '/';
    }

    public function url(): string
    {
        // remove root from path
        $value = \str_replace($this->rootPath(), '', $this->path());
        $value = trim($value, "/");
        $value = $value ? "/$value/" : "/";
        return $value;
    }

    public function parent(): ?Page
    {
        $rootPath = $this->rootPath();
        $parentPath = dirname($this->path()) . "/";

        // check if root is in parent path
        if (strpos($parentPath, $rootPath) === false) {
            return null;
        }

        $url = "/" . str_replace($rootPath, "", $parentPath);
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
        // if ($this->parents instanceof ObjectCollection) {
        //     return $this->parents;
        // }

        // create empty collection
        $this->parents = new ObjectCollection($this->finder);

        $currentPage = $this;

        while ($currentPage->parent() !== null) {
            $this->parents->append($currentPage->parent());
            $currentPage = $currentPage->parent();
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

        $index_array = [];
        $dir = new \FilesystemIterator($this->path());
        foreach ($dir as $file) {
            if ($file->isDir()) {
                $name = $file->getFilename();
                $url = "{$this->url()}{$name}";
                $index_array[] = $url;
                $child = $this->finder->get("Page", $url);
                $children->append($child);
            }
        }

        $this->children = $children;
        return $children;
    }

    public function child(string $name): Page
    {
        $name = trim($name, '/');
        $path = "{$this->path}{$name}/";
        return new Page($path, $this->finder);
    }

    public function files(): array
    {

    }
}
