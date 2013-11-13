<?php

abstract class FieldFormatter {


	public function __construct($field = null) {
		if (is_object($field) && $field instanceof Field) {
            $field->attach($this);
        }
	}

	public function update($field) {
	    // looks for an observer method with the state name
	    if (method_exists($this, $field->get())) {
	        call_user_func_array(array($this, $field->getState()), array($field));
	    }
	}


}