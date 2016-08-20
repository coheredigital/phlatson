<?php

class FieldtypeView extends Fieldtype
{


	public function getOutput($value)
    {
        return "default";
    }

	public function getEdit($value)
    {
    	var_dump($object);
        // return $this->api('config')->paths->views . $object->template->name . ".php";
        return $this->api('config')->paths->views . $object->template->name . ".php";
    }

}