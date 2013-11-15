<?php


class Pages {



	public function get($url){
		$page = new Page($url);
		return $page;
	}

}