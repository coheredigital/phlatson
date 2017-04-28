<?php
namespace Flatbed;

/*

Controller loaded automatically based on the matching template
    example: Template: article loads Controller: /contollers/article.php
    or method spcific if defined: /contollers/article.post.php


Controller is primarily defined to controller to use of construct, set to final to prevent overriding behavious
and is devoid of method so that they can be bound at runtime since Controller extends Flatbed and is bind methods and API access

*/

class Controller extends Flatbed
{

    public $response;

    final public function __construct(Response $response)
    {
        // TODO :  this should not be needed here, temp fix
        $this->response = $response;

        // determine controller file
        $name = $response->template->name;
		$method = $this->request->method;

		if ($response->template->isSystem()) {
			$path = SYSTEM_PATH . "controllers" . DIRECTORY_SEPARATOR;
		}
		else {
			$path = SITE_PATH . "controllers" . DIRECTORY_SEPARATOR;
		}

        
        // check for method specific variation first
        $file = "{$path}{$name}.{$method}.php";

        if (!is_file($file)) {
            $file = "{$path}{$name}.php";
        };

        // no controller file was found, return
        if (!is_file($file)) return;

        // extract named segment variables
        if ($segments = $response->segments(true)) {
            extract($segments);
        }

        // and include controller
        include_once $file;
    }


    // give property access to variable
    public function __get($name) {
        return $this->api($name);
    }

}