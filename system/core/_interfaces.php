<?php

// none yet

interface RecursiveSearch
{


}


// plugin interfaces

// interface Plugin {

// 	// provides listing and other pages or modules with this modules info
// 	public function getInfo();

// }

interface ConfigurablePlugin
{

    // get and set data in /assets/plugins/{$className}.xml
    public function __get($key);

    public function __set($key, $value);

}