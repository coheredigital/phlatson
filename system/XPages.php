<?php 

define("SYSTEM_DIR", dirname(__FILE__) . '/');
require(SYSTEM_DIR . "autoload.php"); 

$config = new Config(); 
// $page = new Page(); 

class XPages{
	
	// define some protected variable to be used by all page objects
	private $requestPath;
	private $filePath;
	public $data;
	public $template;


	function __construct($xmlFile = 0){
		// if ($xmlFile!==0 && !is_file($xmlFile)) die("File does not exist or cannot be found!");
		// else $this->data = simplexml_load_file($xmlFile);
		$this->getPath();
		$this->filePath();
		$this->getData();
		$this->getTemplate();
	}


	private function getPath($uri = false){
		$request = ltrim($_SERVER['REQUEST_URI'], SITE_ROOT);
	    $this->requestPath = $request;
	}	

	private function filePath(){


		if(DIRECTORY_SEPARATOR != '/') $filePath = str_replace(  DIRECTORY_SEPARATOR,'/', CONTENT_DIR.$this->requestPath);
		$filePath = ltrim($filePath, "/");

	    $this->filePath = $filePath."/content.xml";
	}

	public function getData(){
		if (is_file($this->filePath)) {
			// $this->dataObject = "FILE";
			$this->data = simplexml_load_file($this->filePath);
		}
		else{
			$this->dataObject = "NOT FILE";
		}
		

	}

	public function getTemplate(){
		$this->template = "./site/templates/{$this->data->template}.php";
		

	}
}




