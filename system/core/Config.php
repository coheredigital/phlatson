<?php

class Config extends DataArray{

	public function __construct(){
		$this->styles = new FileArray();
		$this->scripts = new FileArray();
	}

}