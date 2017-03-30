<?php

class Page extends DataObject implements ViewableObject
{

    protected $rootFolder = "pages";

    function __construct($file = null)
    {

        parent::__construct($file);

        $this->defaultFields = array_merge($this->defaultFields, [
            "parent"
        ]);

    }

    /**
     * override of Object::getUrl because page are accessed from the 
     * site root URL and note under /site/pages/
     * @return [type] [description]
     */
    public function getUrl() {
        return $this->api('config')->urls->root . ltrim($this->directory, "/");
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
        
        $children = new ObjectCollection();

        $subfolders = glob( $this->path . "*", GLOB_ONLYDIR);

        foreach ($subfolders as $folder) {

            $name = basename($folder);
            $url = "{$this->url}/{$name}";

            $page = $this->api("pages")->get("{$this->url}/{$name}");

            if (!$page instanceof self) continue;
                
            $children->add($page);
            

        }

        return $children;
    }


    protected function getParentUrl()
    {

        $directoryParts = $this->directoryParts();
        array_pop($directoryParts); // remove current (last) item to find parent
        return $this->createUrl($directoryParts);
    }

    protected function getParentPath()
    {
        return dirname($this->path);
    }

    public function parent()
    {

        $parents = new Page();  
        // start with current page
        $parentPath = $this->getParentPath();
        $parent = $this->api("pages")->getByPath($parentPath);

        // get parent and set current page as parent until no parents exist
        return $parent;
    }


    public function parents()
    {

        $parents = new ObjectCollection();  
        // start with current page
        $page = $this;

        // get parent and set current page as parent until no parents exist
        while ($page = $page->parent) {
            $parents->prepend($page);
        }
        return $parents;
    }

    public function rootParent()
    {
        $parents = $this->parents;
        if ($parents->count()) {
            return $parents->last();
        }
        else {
            return $this;
        }
    }

    protected function createUrl($array)
    {
        if (is_array($array) && implode("", $array)) {
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
        if(!$this->template->view) return false;
        return true;
    }

    public function _render(){

        // render template file
        ob_start();

        // add self as page api variable
        $this->api("page", $this);

        // give the rendered page access to the API
        extract($this->api());

        // render found file
        include($this->template->view);

        $output = ob_get_contents(); 
        ob_end_clean();
        return $output;
    }

    public function get( string $name)
    {
        switch ($name) {

            case 'children':
            case 'parent':
            case 'parents':
            case 'rootParent':
            case 'files':
            case 'images':
                return $this->{$name}();
            case 'objectType': // protected / private variable that should have public get
                return $this->{$name};
            default:
                return parent::get($name);
        }

    }


    public function set( string $name, $value )
    {

        // only allow values to be set for existing fields ??
        switch ($name) {
            case "template":
                parent::set($name, $value);
                break;
            default:
                if ($this->template && $this->template->fields && $this->template->fields->has($name)) {
                    $field = $this->api("fields")->get("$name");
                    $fieldtype = $field->fieldtype;
                    $this->data[$name] = $fieldtype->getSave($value);
                } else {
                    parent::set($name, $value);
                }
        }

    }


}