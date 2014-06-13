<?php


class Page extends Object
{

    protected $parent = null;

    protected $root = "pages/";
    protected $defaultFields = array("template","files");

    protected $filesArray;
    protected $imagesArray;

    function __construct($url = null)
    {
        array_merge( $this->properties, array(
                "published" => false,
                "locked"    => false,
                "system"    => true
         ));


        $defaultFields = array();
        foreach ($this->defaultFields as $fieldName) {
            $field = api("fields")->get($fieldName);
            array_push($defaultFields, $field);
        }
        $this->defaultFields = $defaultFields; // replace default fields named array with Objects

        parent::__construct($url);
    }


    protected function setup($path = null)
    {
        // this all need work, its unclear what is happening
        if (is_null($path)) {
            $sitePath = realpath($this->api('config')->paths->site . $this->root . $this->directory) . DIRECTORY_SEPARATOR;
            $systemPath = realpath($this->api('config')->paths->system . $this->root . $this->directory) . DIRECTORY_SEPARATOR;

            if (is_file($sitePath . Object::DATA_FILE)) { // check site path first
                $this->set("path", $sitePath);
                $this->location = "site/";
            } else {
                if (is_file($systemPath . Object::DATA_FILE)) {
                    $this->path = $systemPath;
                    $this->location = "system/";
                }
            }
        } else {

            $path = realpath($path) . DIRECTORY_SEPARATOR;

            if (is_file($path . Object::DATA_FILE)) {
                $this->set("path" , $path);
            }

        }

        $file = $this->path . Object::DATA_FILE;
        if (is_file($file)) {

            $this->data = json_decode(file_get_contents($file), true);
        }
    }


    public function files(){
        return new FileArray($this);
    }

    protected function images(){
        return new ImageArray($this);
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

    protected function parent()
    {

        if(!$this->parent){
            $requests = $this->route;
            array_pop($requests); // remove current (last) item to find parent

            $url = $this->createUrl($requests);

            if ($url) {
                $parent = api("pages")->get($url);
                $this->parent = $parent;
            }
        }

        return $this->parent;

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
            case 'directory':
                $directory = trim(implode("/", $this->route), "/");
                return $directory;
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
            case 'fields':
                return $this->get("template")->getFields($this->defaultFields);
                break;
            case 'files':
                return $this->files();
                break;
            case 'images':
                return $this->images();
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
        switch ($name) {
            case 'parent':
                if($value instanceof Page){
                    $this->parent = $value;
                }
                break;
            default:
                parent::set($name, $value);

        }

    }


}