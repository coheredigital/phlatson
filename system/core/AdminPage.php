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
                return api("extensions")->get( $this->getUnformatted("extension") );
            case 'template':
                $template = new AdminTemplate();
                $template->layout = $this->getUnformatted("layout");
                return $template;

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

