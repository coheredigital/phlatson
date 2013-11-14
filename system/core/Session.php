<?php

class Session implements IteratorAggregate{


	function __construct(){

		@session_start();
		unregisterGLOBALS();
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

	public function getIP($int = false) {
		if(is_null($this->ip)) { 
			if(!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP']; 
				else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				else if(!empty($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR']; 
				else $ip = '';
			$ip = ip2long($ip);
			$this->ip = $ip;
		} else {
			$ip = $this->ip; 
		}
		if(!$int) $ip = long2ip($ip);
		return $ip;
	}

	public function getIterator() {
		return new ArrayObject($_SESSION[$this->className()]); 
	}


	public function login($name,$pass){
		// ....
	}

	public function logout(){
		// ....
	}


}