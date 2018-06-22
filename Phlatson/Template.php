<?php
namespace Phlatson;

class Template extends PhlatsonObject
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

    public function getView() : object
    {
        // get view file of the same name
        $view = new View($this->get('name'));
        return $view;
    }

    public function render() : ? string
    {
        $view = $this->getView();
        return $view->render();
    }

}
