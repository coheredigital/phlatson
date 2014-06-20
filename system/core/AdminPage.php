<?php
/*

Used in the management of Admin Pages and layouts, uses some special feature and overrides that extend from the base Page class

-- override layout discovery methods
-- doesn't require exisiting templates since Admin pages are render using alternative methods

 */


class AdminPage extends Page
{

    // define some protected variable to be used by all page objects
    function __construct($directory, $file)
    {

        parent::__construct($directory, $file);

        $this->set("layout", $this->api('config')->paths->system . "index.php");

        if ($this->route[0] == $this->api('config')->adminUrl) {
            array_shift($this->route);
        }

        $path = realpath($this->api('config')->paths->system . $this->rootFolder . $this->directory) . DIRECTORY_SEPARATOR;
        $this->setup($path);

    }


    public function get($name)
    {
        switch ($name) {
//            case 'url':
//                return $this->api('config')->urls->root . $this->api('config')->adminUrl . "/" . $this->directory;
            case 'extension':
                return $this->getExtension();
            case 'layout':
                $path = $this->api('config')->paths->system . "layouts/";
                $file = $path . $this->getUnformatted("layout") . ".php";
                return $file;
            default:
                return parent::get($name);
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
            return $this->get("extension")->render();
        }
        return false;
    }

}

