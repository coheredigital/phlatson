<?php
/*

Used in the management of Admin Pages and layouts, uses some special feature and overrides that extend from the base Page class

-- override layout discovery methods
-- doesn't require exisiting templates since Admin pages are render using alternative methods

 */


class AdminPage extends Page
{

    public function get($name)
    {
        switch ($name) {
            case 'extension':
                return api::get("extensions")->get( $this->getUnformatted("extension") );
            case 'template':
                $template = new AdminTemplate();
                $template->layout = $this->getUnformatted("layout");
                return $template;

            default:
                return parent::get($name);
        }
    }

    public function rootParent()
    {

        $directory = $this->route[0] . "/" . $this->route[1];

        if ($directory == $this->get("url")) {
            return $this;
        }
        return api::get("pages")->get($directory);

    }


    public function render()
    {
        if ($this->getUnformatted("extension")) {
            return $this->get("extension")->render();
        }
        return false;
    }



}

