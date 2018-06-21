<?php
namespace Flatbed;

class Template extends FlatbedObject
{

    const BASE_FOLDER = 'templates/';
    const BASE_URL = 'templates/';

    public $parent; // the object this template belongs to
    public $defaultFields = ['title', 'fields', 'name', 'view', 'modified'];


    public function hasField($name)
    {
        $fields = $this->data->get('fields');
        return isset($fields[$name]);
    }

    public function getField($name) : ? array
    {
        $fields = $this->data->get('fields');
        return $fields[$name];
    }

    public function getViewFile() : ? string
    {
        $file = SITE_PATH . "views/" . $this->get('name') . ".php";
        return \file_exists($file) ? $file : null;
    }

    public function render() : ? string
    {
        include $this->getViewFile();
    }

}
