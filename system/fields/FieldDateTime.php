<?php

class FieldDateTime extends Field{





	public function format(){
		$this->_value = date($this->_format,$this->_value);
	}

	public function __toString(){

		return $this->_value;
	}


}