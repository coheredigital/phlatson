<?php

class Field extends DataObject{

	protected function setBasePath(){
		return $this->api('config')->paths->fields;
	}
}