<?php
class Input{

	public $url;

	function __construct(){
		$this->_setup();
	}


	protected function _setup(){

		$this->url = isset($_GET['_url']) ? $_GET['_url'] : "";

		$get = new stdClass();
		foreach ($_GET as $key => $value) {
			if ($key == "_url") continue; // skip XPages specific request
			$get->$key = $value;
		}
		if (count((array) $get)) $this->get = $get;



		$post = new stdClass();
		foreach ($_POST as $key => $value) {
			$post->$key = $value;
		}
		if (count((array) $post)) $this->post = $post;



	}


}