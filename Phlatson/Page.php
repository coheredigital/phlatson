<?php
namespace Phlatson;
class Page extends PhlatsonObject
{

    const BASE_FOLDER = 'pages/';
    const BASE_URL = '';

    protected $parent;
    public $template;
    public $routes;

    function __construct($path = null)
    {
        parent::__construct($path);
    }

    public function children() : ObjectCollection
    {

        $children = new ObjectCollection();

        $folders = glob($this->path . "*", GLOB_ONLYDIR | GLOB_NOSORT);

        foreach ($folders as $folder) {
            $page = new Page($folder);
            if (!$page instanceof self) continue;
            $children->add($page);
        }

        return $children;
    }

    public function render()
    {
        return $this->template->render();
    }

}
