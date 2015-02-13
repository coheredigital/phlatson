<?php

/**
 * Class Input
 *
 * Responsible for creation and rendering of Inputs
 */

abstract class Input {

    public $value;
    protected $name;
    protected $attibutes = [];
    protected $field;


    public function __construct(Field $field){
        $this->field = $field;
    }

}