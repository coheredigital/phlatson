<?php

namespace Phlatson;

class Field extends DataObject
{

    public function type()
    {
        $name = $this->data->get('fieldtype');
    }

    public function template(): Template
    {
        $template = $this->api('finder')->get("Template","template");
        return $template;
    }


}
