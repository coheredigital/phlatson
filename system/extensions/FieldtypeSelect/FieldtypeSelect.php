<?php
namespace Flatbed;
class FieldtypeSelect extends Fieldtype implements ProvidesOptions
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