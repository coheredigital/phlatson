<?php


class Fields extends ObjectArray{

	public function __construct(){
		$this->dataFolder = "fields/";
		$this->singularName = "Field";
		$this->load();
	}


}