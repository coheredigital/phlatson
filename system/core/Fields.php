<?php


class Fields{

	public function get($url){
		$page = new Page($url);
		return $page;
	}

}