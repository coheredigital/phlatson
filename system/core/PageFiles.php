<?php


class Page extends Object
{
    protected $dataFolder = "pages/";


    function __construct($url = false)
    {

        parent::__construct($url);

        // $this->baseUrl = $this->getBaseUrl();
        // handle admin page request
        if ($this->urlRequest[0] == $this->api('config')->adminUrl) {
            $this->layout = $this->api('config')->paths->admin . "index.php";
        }

    }

    // protected function setBasePath(){
    // 	return api('config')->paths->content;
    // }


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
            $url = $this->directory . "/" . $folder;

            $path = $this->path . $folder . DIRECTORY_SEPARATOR;
            $file = $path . $this->dataFile;

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
        $requests = $this->urlRequest;
        array_pop($requests); // remove current (last) item to find parent

        $url = $this->createUrl($requests);

        if ($url) {
            $page = new Page($url);
            return $page;
        }
        return false;
    }


    public function parents()
    {
        $requests = $this->urlRequest;
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

        $url = $this->urlRequest[0];

        if ($this->url(false) == $url) {
            return $this;
        } elseif ($url) {
            $page = new $this->className($url);
            return $page;
        }
        return false;
    }


    public function files()
    {
        if (!isset($this->files)) {
            $files = new Files;
            $files->load("$this->directory", 0);
            $this->files = $files;
        }
        return $this->files;

    }

    public function images()
    {

        $files = new Images($this->url(false));
        return $files;

    }


    public function updateFilelist()
    {
        $files = scandir($this->path);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $root = $dom->appendChild($dom->createElement('files'));

        if ($files) {
            foreach ($files as $value) {
                if (is_file($this->path . $value)) {
                    //add NodeA element to Root
                    $fileNode = $dom->createElement('file');

                    $filenameNode = $dom->createElement('filename');
                    $filenameNode->appendChild($dom->createTextNode($value));
                    $fileNode->appendChild($filenameNode);

                    $root->appendChild($fileNode);
                }
            }

            $dom->formatOutput = true;
            $dom->save($this->path . 'files.xml'); // save as file

        }

        return false;

    }

    public function url()
    {
        $url = $this->api('config')->urls->root . $this->directory;
        return $url;
    }

    public function get($name)
    {
        switch ($name) {
            // first pass a few request that we dont want passed to "getFormatted() method"
            // handled by parent
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
            case 'layout':
                // alias for $page->template->layout for simplicity
                $layout = $this->template->layout;
                return $layout ? (string)$layout : null;
                break;
            default:
                return parent::get($name);
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