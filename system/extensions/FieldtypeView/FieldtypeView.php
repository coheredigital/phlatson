<?php

class FieldtypeView extends Fieldtype
{


	public function getOutput($value)
    {
        
    	$viewFile = $this->api('config')->paths->views . $value . ".php";

    	// validate file
    	if (!file_exists($viewFile)) throw new FlatbedException("View file '$viewFile' does not exist.");

    	// render template file
    	ob_start();

    	// give the rendered page access to the API
    	extract($this->api());

    	// render found file
	    include($viewFile);

	    $output = ob_get_contents(); 
	    ob_end_clean();
	    return $output;

    }

	public function getEdit($value)
    {
        return $this->api('config')->paths->views . $value . ".php";
    }

}