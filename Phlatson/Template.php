<?php
namespace Phlatson;

class Template extends PhlatsonObject
{

    const BASE_FOLDER = 'templates/';
    const BASE_URL = 'templates/';

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

    public function get($key)
    {

        switch ($key) {
            case 'view':
                return $this->getView();
            default:
                return parent::get($key);
        }


    }

}
