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



    public function get($name)
    {
        switch ($name) {
            case 'file':
                $file = $this->api('config')->paths->views . $this->value . ".php";
                if (!file_exists($file)) return false;
                return $file;
            default:
                return parent::get($name);
        }

    }


    public function __toString()
    {
        return $this->render();
    }


}