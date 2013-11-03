<?php

define("SYSTEM_DIR", dirname(__FILE__) . '/');

require_once( SYSTEM_DIR . "autoload.php");

class XPages{


	public function __construct(Config $config) {
		$this->_config($config);
	}



	protected function _config(Config $config) {



		ini_set("date.timezone", $config->timezone);
		ini_set('default_charset','utf-8');


		$config->https = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
		$config->ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');

	}


}