<?php

class Field{

	protected $_value;
	protected $_type;
	protected $_format;


	function __construct($v = 0, $t = false, $f = false){
		$this->_value = $v;
		$this->_format = $f;

		$this->format();
	}

	public function format(){
		$this->_value = $this->_value;
	}

}