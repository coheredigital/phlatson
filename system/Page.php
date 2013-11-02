<?php


class Page{


	// define some protected variable to be used by all page objects
	private $data;
	public $template;


	function __construct(){

		$directory = ltrim($_GET['url']);
		$file = CONTENT_DIR.$directory."/content.xml";

		if (is_file($file)) $this->data = simplexml_load_file($file);
		$this->getTemplate();
	}

	private function setup(){

	}




	public function getData($fp){

	}

	public function getTemplate(){
		$this->template = "./site/templates/{$this->data->template}.php";
	}




	public function get($name){
		switch ($name) {
			case 'children':
				$value = $this->children();
				break;
			default:
				$value = $this->data->{$name};
				break;
		}
		return $value;
	}

	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}

}