<?php 

class Templates  extends ObjectArray{


	public function __construct(){
		$this->dataFolder = "/templates/";
		$this->singularName = "Template";
		$this->load();
	}

}