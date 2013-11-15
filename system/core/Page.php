<?php


class Page extends DataObject{

	// define some protected variable to be used by all page objects
	public $layout;

	function __construct($url = false){
		
		parent::__construct($url);

		$this->layout = $this->setTemplate();
		// handle admin page request
		if ($this->pageRequest[0] == $this->config->adminUrl) {
		// if ($this->pageRequest[0] == "admin") {
			$this->layout = $this->config->paths->admin."index.php";
		}

		// $this->template = new Template($this->data->template);

	}


	public function url($fromRoot = true){
		return $this->config->urls->root.$this->directory;
	}


	public function children(){

		// get all subfolder of current page path
		$subs = glob($this->path.DIRECTORY_SEPARATOR."*" , GLOB_ONLYDIR);

		$children = array();
		foreach($subs as $folder) {
			$folder = basename($folder);
     		$url = $this->directory."/".$folder;

     		$page = new Page($url);
     		$children[] = $page;

      	}
      	return $children;
	}

	public function parent(){
		$requests = $this->pageRequest;
		array_pop($requests); // remove current (last) item to find parent

		$url = $this->createUrl($requests);

		if ($url) {
			$page = new Page($url);
	      	return $page;
		}
		return false;
	}


	public function parents(){
		$requests = $this->pageRequest;
		$parents = array();
		$urls = array();

		for ($x=count($requests); $x > 0; $x--) { 
			array_pop($requests);
			$urls[] = $this->createUrl($requests);
		}

		foreach ($urls as $url) {
     		$page = new Page($url);
     		$parents[] = $page;
		}

		return array_reverse($parents);
	}



	public function rootParent(){

		$url = $this->pageRequest[0];

		if ($this->url(false) == $url) {
			return $this;
		}
		elseif ($url) {
			$page = new Page($url);
	      	return $page;
		}
		return false;
	}



	public function files(){

		$files = new Files($this->url(false));
		return $files;

	}

	public function images(){

		$files = new Images($this->url(false));
		return $files;

	}


	protected function formatField($name){

		$field = new Field($name);

		$value = $this->data->{$name};
		if (!$value) return false; // return false if node doesn't exist


		// find the corresponding field file and retrieve relevant settings
		$fieldClassname = (string) $field->fieldtype;
		$fieldFormat = (string) $field->format;


		// override default value for field based on attributes
		// 
		// NOT SURE I EVEN WANT THIS FEATURE, COMMENTED FOR NOW ATLEAST
		// 
		// if($value->attributes) {
		// 	$attr = $value->attributes();
		// 	$fieldClassname = (string) $attr->fieldtype;
		// 	$fieldFormat = (string) $attr->format;
		// }
		
		
		if ($fieldClassname) {
			$fieldtype = new $fieldClassname( );
			$value = $fieldtype->format( $value, $fieldFormat);
		}

		return $value;
	}


	public function updateFilelist(){
		$files = scandir($this->path);
	    $dom = new DOMDocument('1.0', 'UTF-8'); 
	    $root = $dom->appendChild($dom->createElement('files'));

	    if ($files) {
			foreach ($files as $value) {
				if(is_file($this->path.$value)) {
					//add NodeA element to Root
				    $fileNode = $dom->createElement('file');

				   	$filenameNode = $dom->createElement('filename');
				   	$filenameNode->appendChild($dom->createTextNode($value));
				    $fileNode->appendChild($filenameNode); 

				    $root->appendChild($fileNode);
				}
			}

		    $dom->formatOutput = true;
		    $dom->save($this->path.'files.xml'); // save as file

	    }

	    return false;

	}

	public function render(){
		return include $this->layout;
	}



	public function get($name){
		switch ($name) {
			case 'requests':
				$value = $this->pageRequest;
				break;
			case 'children':
				$value = $this->children();
				break;
			case 'parent':
				$value = $this->parent();
				break;
			case 'rootParent':
				$value = $this->rootParent();
				break;
			case 'url':
				$value = $this->url();
				break;
			case 'files':
				$value = $this->files();
				break;
			case 'images':
				$value = $this->images();
				break;
			default:

				$value = $this->formatField($name);
				break;
		}
		return $value;
	}


	public function set($name, $value){
		if ($this->data->{$name}) {
			$this->data->{$name} = $value;
		}
		else{
			$this->{$name} = $value;
		}
		return $value;
	}



}