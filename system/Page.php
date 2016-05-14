<?php

class Page extends Object implements ViewableObject
{

    protected $rootFolder = "pages";

    function __construct($file = null)
    {

        parent::__construct($file);

        $this->defaultFields = array_merge($this->defaultFields, [
            "parent"
        ]);

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



        if ($this->isNew()) {
            return false;
        }
        
        $children = new ObjectCollection();

        $subfolders = glob($this->path . "*", GLOB_ONLYDIR);


        foreach ($subfolders as $folder) {

            $name = basename($folder);

            $url = $this->url . "/" . $name;
            $page = $this->api("pages")->get($url);
            if ($page instanceof Page) {
                $children->add($page);
            }

        }
        return $children;
    }


    public function parents()
    {

        $parents = new ObjectCollection();  

        // start with current page
        $page = $this;

        while ($page = $page->parent) {
            $parents->add($page);
        }

        return $parents->reverse();
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


    /**
     * @return bool
     */
    public function isViewable()
    {
        if($this->isSystem()) return false;
        if(!is_file($this->view)) return false;
        return true;
    }

    public function _render(){

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
            case 'parents':
                return $this->parents();
            case 'rootParent':
                return $this->rootParent();
            case 'files':
                return $this->files();
            case 'images':
                return $this->images();
            case 'view':
                // alias for $page->template->view (required by AdminPage class)
                $view = $this->template->view;
                return $view;
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