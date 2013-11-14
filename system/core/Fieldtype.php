

<?php

class Fieldtype{

	protected $value;
	protected $type;
	protected $format;



	function __construct($v = 0, $t = false, $f = false){
		$this->value = $v;
		$this->format = $f;

		if ($f) $this->format();
	}

	public function format(){
		$this->value = $this->value;
	}


	public function __toString(){
		return (string) $this->value;
	}

	public function getInput($name, $value){
		return "<input class='field-input' type='text' name='$name' id='' value='$value'>";
	}

}