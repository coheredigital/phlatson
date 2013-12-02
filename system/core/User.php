<?php


class User extends DataObject{
	protected $dataFolder = "users/";

	public function isLoggedin(){
		if ($user->name == "guest") return false;
		return true;
	}

}