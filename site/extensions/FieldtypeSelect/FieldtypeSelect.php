<?php

/**
 * Created by PhpStorm.
 * User: aspruijt
 * Date: 22/08/14
 * Time: 12:48 PM
 */
class FieldtypeSelect extends Fieldtype implements OptionsProvider
{

    public $options = [];

    public function options()
    {
        return [];
    }

} 