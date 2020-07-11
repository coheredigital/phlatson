<?php

namespace Phlatson;

class Field extends DataObject
{

    public function type()
    {
        $name = $this->data->get('fieldtype');
    }
}
