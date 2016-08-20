<?php

class FieldtypeView extends Fieldtype
{

	public function getOutput($value)
    {
        
    	$file = $this->api('config')->paths->views . $value . ".php";

    	if (!file_exists($file)) return false;
	    return $file;
    }

	public function getEdit($value)
    {
        return $value;
    }

}