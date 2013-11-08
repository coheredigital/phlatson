<?php

class FieldtypeDateTime extends Fieldtype{

	public function format(){
		$this->_value = date((string) $this->_format, (int) $this->_value);
	}

	public function __toString(){
		return (string) $this->_value;
	}

}