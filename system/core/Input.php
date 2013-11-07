<?php

class Input{

	public $request;


	function __construct(){

		$this->_setup();
		$this->_requests();

	}


	protected function _setup(){

		$get = new stdClass();
		foreach ($_GET as $key => $value) {
			if ($key == "_request") continue; // skip XPages specific request
			$get->$key = $value;
		}
		if (count((array) $get)) $this->get = $get;



		$post = new stdClass();
		foreach ($_POST as $key => $value) {
			$post->$key = $value;
		}
		if (count((array) $post)) $this->post = $post;



	}

	protected function _requests(){

		$url = $_GET["_request"];

		$array = explode("/", $url);
		$this->request = $array;

	}




}