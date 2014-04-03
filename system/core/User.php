<?php

class User extends DataObject{

	protected $dataFolder = "users/";

	/**
	 * is this the guest user?
	 * @return boolean checks if name === guest
	 */
	public function isGuest() {
		return $this->name == "guest"; 
	}

	/**
	 * check if user logged in
	 * @return boolean opposite of isGuest() result
	 */
	public function isLoggedin(){
		return !$this->isGuest();
	}

}