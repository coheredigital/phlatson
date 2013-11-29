<?php 

/* Admin module superclass */

abstract class Admin implements Plugin{


	public static function getInfo() {
		return array(
			'title' => '',		// printable name/title of module
			'version' => 1, 	// version number of module
			'summary' => '', 	// one sentence summary of module
			'href' => '', 		// URL to more information (optional)
			'permanent' => true, 	// true if module is permanent and thus not uninstallable (3rd party modules should specify 'false')
			'permission' => '', 	// name of permission required to execute this Process (optional)
			); 
	}




}