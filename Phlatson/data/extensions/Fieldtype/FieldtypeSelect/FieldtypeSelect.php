<?php
namespace Phlatson;
class FieldtypeSelect extends Fieldtype implements ProvidesOptionsInterface
{

    public $options = [];

    /**
     * gets the key => value array of options to be used in input
     * @return array
     */
    public function options(): array
    {
        return [];
    }

}
