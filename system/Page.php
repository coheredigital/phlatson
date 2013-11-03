<?php


class Page{


	// define some protected variable to be used by all page objects
	protected $_data;
	protected $_path;
	protected $_file;
	protected $_request;

	public $template;
	public $_dirs = array();



	function __construct(){

		$this->_request = $_GET['url'] ? ltrim($_GET['url']) : "";
		if ($this->_request == "admin") {
			$this->template = "./system/admin/index.php";
		}
		else{
			$this->_path = CONTENT_DIR.$this->_request."/";
			$this->_file = $this->_path."content.xml";

			if (is_file($this->_file)) $this->_data = simplexml_load_file($this->_file);
			$this->template = "./site/templates/{$this->_data->template}.php";
		}

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





	public function render(){
		return include $this->template;
	}

	public function get($name){
		switch ($name) {
			case 'children':
				$value = $this->children();
				break;
			default:
				$value = $this->_data->{$name};
				break;
		}
		return $value;
	}

	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}

}