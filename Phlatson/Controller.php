<?php
namespace Phlatson;

/*
Controller loaded automatically based on the matching template
    example: Template: article loads Controller: /contollers/article.php
    or method spcific if defined: /contollers/article.post.php


Controller is primarily defined to controller to use of construct, set to final to prevent overriding behavious
and is devoid of method so that they can be bound at runtime since Controller extends Phlatson and is bind methods and API access
*/

class Controller extends Phlatson
{

    public $template;

    final public function __construct( Template $template )
    {

        $this->template = $template;

        // determine controller file
        $name = $template->name;

        // determine root path based on isSystem() return value
		if ($template->isSystem()) {
			$path = SYSTEM_PATH . "controllers" . DIRECTORY_SEPARATOR;
		}
		else {
			$path = DATA_PATH . "controllers" . DIRECTORY_SEPARATOR;
		}

        
        $file = "{$path}{$name}.php";
        
        // no controller file was found, return
        if (!is_file($file)) return;

        // and include controller
        include_once $file;
    }


    // give property access to variable
    public function __get($name) {
        return $this->api($name);
    }

}