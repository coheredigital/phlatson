<?php 


class JPage{

	public $data;



	function __construct($xmlFile){
		$this->data =  simplexml_load_file($xmlFile);
	}




}