<?php

class Field extends DataObject{
	protected function setBasePath(){
		return api('config')->paths->fields;
	}
}