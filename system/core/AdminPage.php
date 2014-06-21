<?php
/*

Used in the management of Admin Pages and layouts, uses some special feature and overrides that extend from the base Page class

-- override layout discovery methods
-- doesn't require exisiting templates since Admin pages are render using alternative methods

 */


class AdminPage extends Page
{

    // define some protected variable to be used by all page objects
    function __construct($file, $directory)
    {

        parent::__construct($file, $directory);


//        $template = new Template(); // create blank template
//        $template->layout = $this->getUnformatted("layout");
//        $this->template = $template;


    }



    public function get($name)
    {
        switch ($name) {
            case 'extension':
                return api("extensions")->get($this->getUnformatted("extension"));
            case 'layout':
                return api("config")->paths->systemLayouts . $this->getUnformatted("layout") . ".php";
            default:
                return parent::get($name);
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

