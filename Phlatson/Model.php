<?php
namespace Phlatson;

class Model extends DataObject
{

    const BASE_FOLDER = 'templates/';
    const BASE_URL = 'templates/';

    public function hasField($name)
    {
        $fields = $this->data('fields');
        return isset($fields[$name]);
    }

    public function getField($name) : ? array
    {
        $fields = $this->data('fields');
        return $fields[$name];
    }

    public function view() : object
    {
        // get view file of the same name
        $name = $this->get('name');
        $view = new View($name);
        return $view;
    }

    public function get(string $key)
    {

        switch ($key) {
            case 'view':
                return $this->view();
            default:
                return parent::get($key);
        }

    }

}
