<?php
namespace Phlatson;
class Page extends DataObject
{

    const BASE_FOLDER = 'pages/';
    const BASE_URL = '';

    protected $parent;

    public function children() : ObjectCollection
    {

        $children = new PageCollection();

        $folders = glob($this->path . "*", GLOB_ONLYDIR);

        foreach ($folders as $folder) {
            $folder = str_replace($this->rootPath, "", $folder);
            $folder = "/" . trim($folder, "/") . "/";
            $page = new Page($folder);
            if (!$page instanceof self) continue;
            $children->append($page);
        }

        return $children;
    }

}
