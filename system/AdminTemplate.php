<?php
/*

Used in the management of Admin page rendering and layouts, uses some special feature and overrides that extend from the base Template class

-- override layout discovery methods
-- doesn't require existing templates since Admin pages are render using alternative methods

 */


class AdminTemplate extends Template
{

    // override Template name
    function __construct()
    {
        $this->name = 'admin';
    }

    public function get($name)
    {
        switch ($name) {
            case 'extension':
                $extensionName = $this->getUnformatted("extension");
                return api::get("extensions")->get( $extensionName );
            case 'layout':
                $layoutName = $this->getUnformatted("layout");
                $layoutFile = api::get("config")->paths->layouts . "admin/". $layoutName . ".php";
                return $layoutFile;
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