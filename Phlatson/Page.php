<?php
namespace Phlatson;

class Page extends DataObject
{

    const BASE_FOLDER = 'pages/';
    const BASE_URL = '';

    protected $parent;
    protected $children;

    public function children() : PageCollection
    {
        $url = $this->url;
        $children = $this->children;

        if ($children instanceof PageCollection) {
            return $children;
        }

        $children = new PageCollection();
        $dir = new \FilesystemIterator($this->path);

        foreach ($dir as $file) {
            if ($file->isDir()) {
                $name = $file->getFilename();
                $children->append("{$this->url}{$name}");
            }
        }

        $this->children = $children;
        return $children;
    }

    public function child(string $name) : Page
    {
        $name = trim($name, "/");
        $path = "{$this->url}{$name}/";

        $page = new Page($path);
        return $page;
    }


}
