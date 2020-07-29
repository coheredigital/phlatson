<?php

namespace Phlatson;

class Field extends DataObject
{

    public function type() : Fieldtype
    {
        $name = $this->data->get('fieldtype');
        $fieldtype = $this->api('finder')->get("Fieldtype", $name);
        // TODO: reevaluate return the default base Fieldtype
        if (!$fieldtype) {
            $fieldtype = new Fieldtype();
        }
        return $fieldtype;
    }

    public function template(): Template
    {
        $template = $this->api('finder')->get("Template", "template");
        return $template;
    }
}
