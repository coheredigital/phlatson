<?php 


class Page{


	// define some protected variable to be used by all page objects
	private $data;
	public $template;


	function __construct(){

		$uri = ltrim($_SERVER['REQUEST_URI'], SITE_ROOT);

		if(DIRECTORY_SEPARATOR != '/') 
			$file = str_replace(  DIRECTORY_SEPARATOR,'/', CONTENT_DIR.$uri);
		$file = ltrim($file, "/")."/content.xml";

		if (is_file($file))
			$this->data = simplexml_load_file($file);
		$this->getTemplate();
	}

	private function setup(){

	}




	public function getData($fp){
		
	}

	public function getTemplate(){
		$this->template = "./site/templates/{$this->data->template}.php";
	}


	public function __get($name){
		if ($this->data->{$name}) return $this->data->{$name};
	}	

}