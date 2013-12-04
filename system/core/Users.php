<?php


class Users extends ObjectArray{

	protected $root = "users/";
	protected $singularName = "User";



	public function setActiveUser(User $user){
		$this->currentUser = $user; 
		$this->api('user', $user); 
	}

}