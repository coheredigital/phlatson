<?php

namespace Phlatson;

class FieldtypeText extends Fieldtype
{
    public function decode($value)
    {
        return $value;
    }
}
