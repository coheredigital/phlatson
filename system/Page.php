<?php


class Page{


	// define some protected variable to be used by all page objects
	private $_data;
	public $template;


	function __construct(){

		$directory = $_GET['url'] ? ltrim($_GET['url']) : "";
		if ($directory == "admin") {
			$this->template = "./system/admin/admin.php";
		}
		else{
			$file = CONTENT_DIR.$directory."/content.xml";

			if (is_file($file)) $this->data = simplexml_load_file($file);
			$this->template = "./site/templates/{$this->data->template}.php";
		}

	}








	public function render(){
		return include $this->template;
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