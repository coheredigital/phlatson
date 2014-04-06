<?php

/*

	Template extends fieldgroup because its is essentially a fieldgroup with a layout and more settings

 */


class Template extends Fieldset
{
    protected $dataFolder = "templates/";

    private function getLayout()
    {
        $layoutFile = $this->api('config')->paths->layouts . $this->name . ".php";
        $layoutFile = is_file($layoutFile) ? $layoutFile : null;
        return $layoutFile;
    }

    public function get($name)
    {
        switch ($name) {
            case 'template':
                return $this->getTemplate("template");
                break;
            case 'layout':
                return $this->getLayout();
                break;
            default:
                return parent::get($name);
                break;
        }
    }
}