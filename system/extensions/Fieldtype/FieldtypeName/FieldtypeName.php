<?php
namespace Flatbed;
class FieldtypeName extends Fieldtype
{

    public function getSave($value)
    {
        $value = Filter::name($value);
        return $value;
    }

}