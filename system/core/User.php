<?php


class User extends DataObject{

	public $name = "guest";

	protected function setBasePath(){
		return $this->api('config')->paths->users;
	}

	public function isLoggedin(){
		if ($user->name == "guest") return false;
		return true;
	}

}