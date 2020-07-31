<?php

namespace Phlatson;

class Template extends DataObject
{

    protected DataObject $owner;

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

    public function setOwner(DataObject $owner): void
    {
        $this->owner = $owner;
    }

    public function view(): View
    {
        // get view file of the same name
        $view = new View($this->name(), $this);
        return $view;
    }

    public function template(): Template
    {
        $template = $this->api('finder')->get("Template", "template");
        return $template;
    }
}
