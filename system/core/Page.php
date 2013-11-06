<?php


class Page{


	// define some protected variable to be used by all page objects
	protected $_data;
	protected $_path;
	protected $_file;
	protected $_request;

	public $url;
	public $template;




	function __construct($url = false){
		$this->_request = trim($url ? (string) $url : (string) $_GET['_request']);
		// $this->_request = ltrim($this->_request, "/");

		$this->url = $this->_request ? SITE_ROOT.$this->_request : SITE_ROOT;


		$this->_path = $this->_request ? str_replace(DIRECTORY_SEPARATOR, '/', CONTENT_DIR.$this->_request.DIRECTORY_SEPARATOR) : str_replace(DIRECTORY_SEPARATOR, '/', CONTENT_DIR);
		$this->_file = "{$this->_path}content.xml";

		$this->_hasFile = 1;
		$this->_data = simplexml_load_file($this->_file);



		if ($this->_data) $this->_setTemplate($this->_data);
	}

	protected function _setTemplate($data){
		$this->template = "./site/layouts/{$this->_data->template}.php";
	}



	public function children(){

		$subdirectories = glob("{$this->_path}*" , GLOB_ONLYDIR);

		$children = array();
		foreach($subdirectories as $folder) {
			$folder = str_replace("/", DIRECTORY_SEPARATOR, $folder);
			$folder = rtrim($folder, DIRECTORY_SEPARATOR);

     		$url = str_replace(CONTENT_DIR, '', $folder).DIRECTORY_SEPARATOR;
     		$url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
     		$url = ltrim($url, '/');

     		$page = new Page($url);
     		$children[] = $page;


      	}
      	return $children;
	}


	protected function _formatField($name){

		$value = $this->_data->{$name};
		if (!$value) return false; // return false if node doesn't exist

		$fieldGet = $this->getFieldXML($name);


		if ($fieldGet){
			$fieldtype = (string) $fieldGet->fieldtype;
			$format = (string) $fieldGet->format;
		}

		if($value->attributes) {
			$attributes = $value->attributes();
			$fieldtype = (string) $value->attributes()->fieldtype;
			$format = (string) $value->attributes()->format;
		}

		if ($fieldtype) {
			$field = new $fieldtype( (string) $value, $fieldtype, $format);
			$value = $field;
		}
		else{
			$value = $this->_data->{$name};
		}



		return $value;
	}



	public function getFieldXML($name){
		$file = SITE_DIR."fields/{$name}.xml";
		if (is_file($file))
			return simplexml_load_file($file);
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
				$value = $this->_formatField($name);
				break;
		}
		return $value;
	}

	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}

	public function __toString(){
		return (string) $this->url;
	}

}