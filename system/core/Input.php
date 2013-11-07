<?php

class Input{
	function __construct(){
		$this->_setup();
	}


	protected function _setup(){

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