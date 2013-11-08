<?php

class Session extends X{



	public function __construct() {

		// $this->config = $this->fuel('config'); 
		$this->init();
		unregisterGLOBALS();
		$className = $this->className();
		$user = null;

		if(empty($_SESSION[$className])) $_SESSION[$className] = array();

		if($userID = $this->get('_user_id')) {
			if($this->isValidSession()) {
				// $user = $this->fuel('users')->get($userID); 
			} else {
				$this->logout();
			}
		}

		// if(!$user || !$user->id) $user = $this->fuel('users')->getGuestUser();
		// $this->fuel('users')->setCurrentUser($user); 	

		foreach(array('message', 'error') as $type) {
			if($items = $this->get($type)) foreach($items as $item) {
				list($text, $flags) = $item;
				parent::$type($text, $flags); 
			}
			$this->remove($type);
		}

		// $this->setTrackChanges(true);
	}






	/**
	 * Start the session
	 *
	 * Provided here in any case anything wants to hook in before session_start()
	 * is called to provide an alternate save handler.
	 *
	 */
	protected function init() {
		@session_start();
	}

	/**
	 * Get a session variable
	 *
	 * @param string $key
	 * @return mixed
	 *
	 */
	public function get($key) {
		$className = $this->className();
		return isset($_SESSION[$className][$key]) ? $_SESSION[$className][$key] : null; 
	}


	/**
	 * Unsets a session variable
	 *
 	 * @param string $key
	 * @return $this
	 *
	 */
	public function remove($key) {
		unset($_SESSION[$this->className()][$key]); 
		return $this; 
	}


}

