<?php

namespace Phlatson;

class Template extends DataObject
{

    public function hasField($name)
    {
        $fields = $this->data('fields');
        return isset($fields[$name]);
    }

    public function getField($name): ?Field
    {
        $fields = $this->data('fields');
        return $fields[$name];
    }

    public function view(): View
    {
        // get view file of the same name
        return new View($this->name());
    }

}
