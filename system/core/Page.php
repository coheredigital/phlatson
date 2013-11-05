<?php


class Page{


	// define some protected variable to be used by all page objects
	protected $_data;
	protected $_path;
	protected $_file;

	public $url;
	public $template;
	public $_dirs = array();



	function __construct($url = false){

		// $this->_request = $url ? $url : $_GET['url'];
		$this->_request = $_GET['url'];
		$this->url = SITE_ROOT.$url;

		if ($this->_request == "admin") $this->template = "./system/admin/index.php";
		else{
			$this->_path = CONTENT_DIR.$this->_request."/";
			$this->_file = $this->_path."content.xml";
			if (is_file($this->_file)) $this->_data = simplexml_load_file($this->_file);
		}


		if ($this->_data) $this->_setTemplate($this->_data);
	}

	protected function _setTemplate($data){
		$this->template = "./site/layouts/{$this->_data->template}.php";
	}



	public function children(){

		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($this->_path), RecursiveIteratorIterator::SELF_FIRST);

		foreach($iterator as $file) {
         	if($file->isDir()) {

         		$page = array();

         		// path
         		$path = $file->getRealpath();
         		$page['path'] = $path;


				$path2 = PHP_EOL;
				$path3 = $path.$path2;
				$array = explode('/', $path3);
				$result = end($array);
				// name
				$page['name'] = basename($result);
				$this->_dirs[] = $page;
           }
      	}
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

}