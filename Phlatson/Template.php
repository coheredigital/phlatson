<?php

namespace Phlatson;

class Template extends DataObject
{

    public function hasField($name)
    {
        $fields = $this->data('fields');
        return isset($fields[$name]);
    }

    public function getField($name): ?array
    {
        $fields = $this->data('fields');
        return $fields[$name];
    }

    public function view(): object
    {
        // get view file of the same name
        $name = $this->name();
        $view = new View($name);
        return $view;
    }

    public function get($key)
    {

        switch ($key) {
            case 'view':
                return $this->view();
            default:
                return parent::get($key);
        }
    }
}
