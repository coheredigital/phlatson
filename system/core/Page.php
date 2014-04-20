<?php


class Page extends Object
{
    protected $root = "pages/";
    protected $defaultFields = array("template");

    function __construct($url = false)
    {
        $defaultFields = array();
        foreach ($this->defaultFields as $fieldName) {
            $field = api("fields")->get($fieldName);
            array_push($defaultFields, $field);
        }
        $this->defaultFields = $defaultFields; // replace default fields named array with Objects

        parent::__construct($url);
    }



    public function children()
    {

        if ($this->path === null) {
            return;
        } // break out if no valid path
        // get all subfolder of current page path

        $subs = glob($this->path . "*", GLOB_ONLYDIR);


        $children = array();
        foreach ($subs as $folder) {
            $folder = basename($folder);
            $url = $this->get("directory") . "/" . $folder;

            $path = $this->path . $folder . DIRECTORY_SEPARATOR;
            $file = $path . Object::DATA_FILE;

            // skip if no "dataFile" is found
            if (!is_file($file)) {
                continue;
            }

            // get an new of same class, useful for extending into AdminPage, etc
            $page = new $this->className($url);

            // pass the Page to $children array, use url as key to avoid duplicates
            // should be imposible for any to items to return the same url
            $children["$page->url"] = $page;

        }
        return $children;
    }

    public function parent()
    {

        $requests = $this->route;
        array_pop($requests); // remove current (last) item to find parent

        $url = $this->createUrl($requests);

        if ($url) {
            $parent = api("pages")->get($url);
            return $parent;
        }
        return false;

    }


    public function parents()
    {

        $requests = $this->route;
        $parents = array();
        $urls = array();

        for ($x = count($requests); $x > 0; $x--) {
            array_pop($requests);
            $urls[] = $this->createUrl($requests);
        }

        foreach ($urls as $url) {
            $page = new $this->className($url);
            $parents[] = $page;
        }

        return array_reverse($parents);
    }


    public function rootParent()
    {

        $name = $this->route[0];

        if ($name == $this->get("url")) {
            return $this;
        } else if ($name) {
            $page = new $this->className($name);
            return $page;
        }
        return false;
    }



    protected function createUrl($array)
    {
        if (is_array($array) && implode("", $this->route)) {
            $url = "/" . implode("/", $array);
            return $url;
        }
        return null;
    }

    public function get($string)
    {
        switch ($string) {
            // first pass a few request that we dont want passed to "getFormatted() method"
            // handled by parent
            case 'url':
                return $this->api('config')->urls->root . $this->directory;
                break;
            case 'children':
                return $this->children();
                break;
            case 'parent':
                return $this->parent();
                break;
            case 'rootParent':
                return $this->rootParent();
                break;
            case 'files':
                return $this->files();
                break;
            case 'images':
                return $this->images();
                break;
            case 'fields':
                return $this->get("template")->getFields($this->defaultFields);
                break;
            case 'layout':
                // alias for $page->template->layout for simplicity
                $template = $this->get("template");
                $layout = $template->get("layout");
                return $layout ? (string)$layout : null;
                break;
            default:
                return parent::get($string);
                break;
        }

    }

    public function set($name, $value)
    {
        if ($this->data->{$name}) {
            $this->data->{$name} = (string)$value;
        } else {
            $this->{$name} = $value;
        }
        return $value;
    }


}