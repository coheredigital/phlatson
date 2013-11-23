<?php


class Page extends DataObject{

	// define some protected variable to be used by all page objects
	protected $baseUrl; // only pages with need a url

	function __construct($url = false){
		
		parent::__construct($url);

		$this->baseUrl = $this->setBaseUrl();

		// handle admin page request
		if ($this->urlRequest[0] == $this->api('config')->adminUrl) {
			$this->layout = $this->api('config')->paths->admin."index.php";
		}	

	}

	protected function setBasePath(){
		return api('config')->paths->content;
	}	
	protected function setBaseUrl(){
		return api('config')->urls->root;
	}

	public function url($fromRoot = true){
		// var_dump($this->baseUrl.$this->directory);
		
		return $this->baseUrl.$this->directory;
	}


	public function children(){

		// get all subfolder of current page path
		$subs = glob($this->path.DIRECTORY_SEPARATOR."*" , GLOB_ONLYDIR);

		$children = array();
		foreach($subs as $folder) {
			$folder = basename($folder);
     		$url = $this->directory."/".$folder;
     		$dataFile = trim($this->path, "/")."/".$folder."/".$this->dataFile;
     		// skip if no "dataFile" is found
     		if (!is_file($dataFile)) continue;


     		// get an new of same class, useful for extending into AdminPage, etc
     		$page = new $this->className($url);
  
     		
     		$children[] = $page;

      	}
      	return $children;
	}

	public function parent(){
		$requests = $this->urlRequest;
		array_pop($requests); // remove current (last) item to find parent

		$url = $this->createUrl($requests);

		if ($url) {
			$page = new Page($url);
	      	return $page;
		}
		return false;
	}


	public function parents(){
		$requests = $this->urlRequest;
		$parents = array();
		$urls = array();

		for ($x=count($requests); $x > 0; $x--) { 
			array_pop($requests);
			$urls[] = $this->createUrl($requests);
		}

		foreach ($urls as $url) {
     		$page = new $this->className($url);
     		$parents[] = $page;
		}

		return array_reverse($parents);
	}



	public function rootParent(){

		$url = $this->urlRequest[0];

		if ($this->url(false) == $url) {
			return $this;
		}
		elseif ($url) {
			$page = new $this->className($url);
	      	return $page;
		}
		return false;
	}



	public function files(){
		if(!isset($this->files)){
			$files = new Files;
			$files->load("$this->directory", 0);
			$this->files = $files;
		}
		return $this->files;

	}

	public function images(){

		$files = new Images($this->url(false));
		return $files;

	}

	public function save($input){
		// clone the object so we can safely overwrite values
		$this->saveDate = clone $this->data;
		$template = $this->getTemplate();
		
		foreach ($template->fields() as $f) {
			$field = new Field("$f");
			$value = $input->{$field->name};

			

			$fieldtype = $field->type();
			$value = $fieldtype->saveFormat($value);
			
			$this->saveDate->{$field->name} = $value;

		}
		$this->saveDate->saveXML($this->path.$this->dataFile );
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


	public function get($name){
		switch ($name) {
			case 'requests':
				$value = $this->urlRequest;
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
			case 'template':
				$value = $this->getTemplate();
				break;
			case 'layout':
				// alias for $page->template->layout for ease of use
				// var_dump($this->template->layout);
				$template = $this->template;
				$layout =  $template->layout;
				$value = $layout ? (string) $layout : null;
				break;
			default:
				$value = $this->formatField($name);
				break;
		}
		if (!$value) {
			// fall back to parent if we failed to find anything
			$value = parent::get($name);
		}
		return $value;
	}


	public function set($name, $value){
		if ($this->data->{$name}) {
			$this->data->{$name} = (string) $value;
		}
		else{
			$this->{$name} = $value;
		}
		return $value;
	}



}