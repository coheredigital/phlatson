<?php
/*

Used in the management of Admin Pages and layouts, uses some special feature and overrides that extend from the base Page class

-- override layout discovery methods
-- doesn't require exisiting templates since Admin pages are render using alternative methods

 */


class AdminPage extends Page
{

    // define some protected variable to be used by all page objects
    function __construct($url = false)
    {
        parent::__construct($url);
        $this->set("layout", $this->api('config')->paths->admin . "index.php");

        if ($this->urlRequest[0] == $this->api('config')->adminUrl) {
            array_shift($this->urlRequest);
        }

        $path = realpath($this->api('config')->paths->admin . "pages/{$this->directory}") . DIRECTORY_SEPARATOR;
        $this->setupData($path);

    }


    public function url()
    {
        return $this->api('config')->urls->root . $this->api('config')->adminUrl . "/" . $this->directory;
    }


    public function get($string)
    {
        switch ($string) {
            case 'extension':
                return $this->getExtension();
                break;
            case 'layout':
                $path = $this->api('config')->paths->admin . "layouts/";
                $file = $this->getUnformatted("layout") . ".php";
                return $path . $file;
                break;
            default:
                return parent::get($string);
                break;
        }
    }


    protected function getExtension()
    {
        if ($this->getUnformatted("extension")) {
            $extension = api("extensions")->get($this->getUnformatted("extension"));
            return $extension;
        }
    }


    public function render()
    {
        if ($this->getUnformatted("extension")) {
            return $this->extension->render();
        }
        return false;
    }

}

