<?php

class f{

	static public function truepath($path){
	    // whether $path is unix or not
	    $unipath=strlen($path)==0 || $path{0}!='/';
	    // attempts to detect if path is relative in which case, add cwd
	    if(strpos($path,':')===false && $unipath)
	        $path=getcwd().DIRECTORY_SEPARATOR.$path;
	    // resolve path parts (single dot, double dot and double delimiters)
	    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
	    $absolutes = array();
	    foreach ($parts as $part) {
	        if ('.'  == $part) continue;
	        if ('..' == $part) {
	            array_pop($absolutes);
	        } else {
	            $absolutes[] = $part;
	        }
	    }
	    $path=implode(DIRECTORY_SEPARATOR, $absolutes);
	    // resolve any symlinks
	    if(file_exists($path) && linkinfo($path)>0)$path=readlink($path);
	    // put initial separator that could have been lost
	    $path=!$unipath ? '/'.$path : $path;
	    return $path;
	}



}


function x($name = 'x') {
	return X::getFuel($name);
}


/**
 * Emulate register globals OFF
 *
 * Should be called after session_start()
 *
 * This function is from the PHP documentation at: 
 * http://www.php.net/manual/en/faq.misc.php#faq.misc.registerglobals
 *
 */
function unregisterGLOBALS() {

	if(!ini_get('register_globals')) {
		return;
	}

	// Might want to change this perhaps to a nicer error
	if(isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
		die();
	}

	// Variables that shouldn't be unset
	$noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());

	foreach ($input as $k => $v) {
		if(!in_array($k, $noUnset) && isset($GLOBALS[$k])) {
	    		unset($GLOBALS[$k]);
		}
	}
}