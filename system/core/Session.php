<?php

class Session extends Core implements IteratorAggregate{

	public $className;

	function __construct(){

		@session_start();
		unregister_GLOBALS();
		$className = $this->className();

		if(empty($_SESSION[$className])) $_SESSION[$className] = array();

	}

	public function set($key, $value) {
		$className = $this->className();
		$oldValue = $this->get($key);
		$_SESSION[$className][$key] = $value; 
		return $this; 
	}

	public function get($key) {
		$className = $this->className();
		return isset($_SESSION[$className][$key]) ? $_SESSION[$className][$key] : null; 
	}

	public function remove($key) {
		unset($_SESSION[$this->className()][$key]); 
		return $this; 
	}

	public function __get($key) {
		return $this->get($key); 
	}
	public function __set($key, $value) {
		return $this->set($key, $value); 
	}

	public function className() {
		if(!$this->className) $this->className = get_class($this);
		return $this->className;
	}

	public function redirect($url, $permanent = true) {

		// perform the redirect
		if($permanent) header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url");
		header("Connection: close"); 
		exit(0);
	}

	public function getIterator() {
		return new ArrayObject($_SESSION[$this->className()]); 
	}


	public function login($name, $pass) {

		// should sanitize name
		$user = $this->api('users')->get("$name"); 
		if (!$user instanceof User) return null;
		if($user->pass == $pass) { 
			session_regenerate_id(true);
			$this->set('_user_name', $user->name); 
			$this->set('_user_time', time());
			$this->setApi('user', $user); 
			return $user; 
		}

		return null; 
	}

	public function logout(){
		// ....
	}


}