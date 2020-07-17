<?php

namespace Phlatson;

class Field extends DataObject
{

    public function type()
    {
        $name = $this->data->get('fieldtype');
        // $fieldtype = $this->api('finder')->get("Fieldtype", $name);
    }

    public function template(): Template
    {
        $template = $this->api('finder')->get("Template", "template");
        return $template;
    }
}
