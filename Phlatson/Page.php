<?php

namespace Phlatson;

class Page extends DataObject
{
    const BASE_FOLDER = 'pages/';
    const BASE_URL = '';

    protected $parent;
    protected $children;
    protected $parents;

    public function parent() : Page
    {
        $root = $this->rootPath();
        $parent = dirname($this->path()) . "/";

        $url = "/" . str_replace($root, "", $parent);
        $url = rtrim($url, '/') . '/';

        $page = new Page($url);
        if ($page->exists()) {
            return $page;
        }

    }

    protected function rootFolder()
    {
        return str_replace($this->url(), '', $this->folder()) . '/';
    }

    protected function url()
    {
        // remove root from path
        $value = \str_replace($this->rootPath(), '', $this->path());
        $value = trim($value, "/");
        $value = $value ? "/$value/" : "/";
        return $value;
    }

    public function parents() : ObjectCollection
    {
        $parents = $this->parents;

        // skip if already stored
        if ($parents instanceof ObjectCollection) {
            return $parents;
        }

        // create empty collection
        $parents = new ObjectCollection();

        $currentPage = $this;

        while ($currentPage->parent() !== null) {
            $parents->append($currentPage->parent());
            $currentPage = $currentPage->parent();
        }

        // cache result
        $this->parents = $parents->reverse();
        return $parents;
    }

    public function children() : ObjectCollection
    {
        $url = $this->url;
        $children = $this->children;

        // skip if already stored
        if ($children instanceof ObjectCollection) {
            return $children;
        }

        // create empty collection
        $children = new ObjectCollection();

        $folder_index = [];
        // $folder_index = Filemanager::getData($this->folder, "index");

        if (count($folder_index)) {
            $children->import($folder_index);
        } else {
            $index_array = [];
            $dir = new \FilesystemIterator($this->path);
            foreach ($dir as $file) {
                if ($file->isDir()) {
                    $name = $file->getFilename();
                    $url = "{$this->url}{$name}";
                    $index_array[] = $url;
                    $children->append($url);
                }
            }

            // Filemanager::saveData($index_array, $this->folder, "index");
        }

        $this->children = $children;
        return $children;
    }

    public function child(string $name) : Page
    {
        $name = trim($name, '/');
        $path = "{$this->path}{$name}/";

        $page = new Page($path);
        return $page;
    }
}
