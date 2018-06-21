<?php
namespace Phlatson;
class FieldtypeName extends Fieldtype
{

    public function getSave($value)
    {
        $value = Filter::name($value);
        return $value;
    }

}