<?php


class Page extends Object
{

    protected $rootFolder = "pages";
    public $defaultFields = array("name", "template", "parent");

    function __construct($file = null)
    {

        parent::__construct($file);

        // set parent page value
        if (!$this->isNew()) {
            $parentUrl = $this->getParentUrl();
            $this->setUnformatted("parent", $parentUrl);
        }

    }

    protected function getParentUrl()
    {
        $requests = $this->route; // make a copy as to not alter route
        array_pop($requests); // remove current (last) item to find parent
        return $this->createUrl($requests);
    }

    protected function getNewName()
    {
        // set object name
        if ($this->template->_settings->nameFrom && $this->template->fields->has(
                $this->settings->nameFrom
            )
        ) { // TODO : this is not in yet, we need support for creating the name from referencing another field
            return $this->api("sanitizer")->name($this->settings->nameFrom);
        } else {
            return $this->api("sanitizer")->name($this->title);
        }

    }

    public function files()
    {
        return new FileCollection($this);
    }

    protected function images()
    {
        return new ImageArray($this);
    }

    public function children()
    {

        if ($this->path === null) {
            return;
        }

        // break out if no valid path
        // get all subfolder of current page path
        // TODO: improve validation of existing Object, unless new, a path being none existing should throw an exception

        $subs = glob($this->path . "*", GLOB_ONLYDIR);

        $children = array();
        foreach ($subs as $folder) {

            $url = $this->get("directory") . "/" . basename($folder);
            $page = $this->api("pages")->get($url);
            if ($page instanceof Page) {
                // pass the Page to $children array, use url as key to avoid duplicates
                // should be impossible for any to items to return the same url
                $children["$page->directory"] = $page;
            }

        }
        return $children;
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
            $page = $this->api("pages")->get($url);
            $parents[] = $page;
        }

        return array_reverse($parents);
    }

    public function rootParent()
    {
        $name = $this->route[0];
        if ($name == $this->get("url")) {
            return $this;
        }
        return $this->api("pages")->get($name);
    }

    protected function createUrl($array)
    {
        if (is_array($array) && implode("", $this->route)) {
            $url = "/" . implode("/", $array);
            return $url;
        }
        return null;
    }

    public function get($name)
    {
        switch ($name) {
            case 'uri':
            case 'directory':
                $directory = implode("/", $this->route);
                return Filter::uri($directory);
            case 'url':
                return $this->api('config')->urls->root . ltrim($this->directory, "/");
            case 'children':
                return $this->children();
            case 'rootParent':
                return $this->rootParent();
            case 'files':
                return $this->files();
            case 'images':
                return $this->images();
            case 'layout':
                // alias for $page->template->layout (required by AdminPage class)
                $layout = $this->template->layout;
                return $layout;
            case 'objectType': // protected / private variable that should have public get
                return $this->{$name};
            default:
                return parent::get($name);
        }

    }


    public function set($name, $value)
    {

        // only allow values to be set for existing fields ??
        switch ($name) {
            case "template":
                parent::set($name, $value);
                break;
            default:
                if ($this->template && $this->template->fields->has($name)) {
                    $field = $this->api("fields")->get("$name");
                    $fieldtype = $field->type;
                    $this->data[$name] = $fieldtype->getSave($value);
                } else {
                    parent::set($name, $value);
                }
        }


    }


}