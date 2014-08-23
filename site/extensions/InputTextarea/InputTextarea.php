<?php
/**
 * Created by PhpStorm.
 * User: aspruijt
 * Date: 22/08/14
 * Time: 12:48 PM
 */

class InputTextarea extends Input{

    protected $attributes = [
        "rows" => 10
    ];

    protected function renderInput()
    {
        $attributes = $this->getAttributes();
        $output = "<textarea $attributes >$this->value</textarea>";
        return $output;
    }

} 