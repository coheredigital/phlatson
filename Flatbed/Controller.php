<?php
namespace Flatbed;

/*

Controller loaded automatically based on the matching template
    example: Template: article loads Controller: /contollers/article.php
    or method spcific if defined: /contollers/article.post.php

IDEA : named segment types extended to support
    - (controller:name)     - changes the controller that will be loaded for the template
    - (method:name)         - fires the given method
    - (page:url)           - changes that page variable
                                - default to "all" capture type
                                - TODO:  determine what if anything could be done with this
                                - could just return a $page for the name variable : YES
    - (user:name)           - support all object types as above? 
    - (view:name)           


Controllers can change the page that is returned
    request to /blog/2017/04/27/the-new-site-is-online
    would be a segment that matched to blog template / page
    but the Controller can intercept the request and match it to the blog post /blog/20170427-the-new-site-is-online
    returning it with its template and page etc
    this is handled by $this->page() method

*/

class Controller extends Flatbed
{

    final public function __construct(Response $response)
    {
        $file = $this->getFile($response->template);
        $this->execute($file,$response);
    }

	protected function getFile( Template $template) 
	{


		if ($template->isSystem()) {
			$rootPath = SYSTEM_PATH . "controllers" . DIRECTORY_SEPARATOR;
		}
		else {
			$rootPath = SITE_PATH . "controllers" . DIRECTORY_SEPARATOR;
		}
		$name = $template->name;
		$method = $this->request->method;

        $file = "{$rootPath}{$name}.{$method}.php";
        if (is_file($file)) return $file;

        $file = $rootPath . $name . ".php";
        if (is_file($file)) return $file;

        return null;

	}


    public function execute($file,$response)
    {

        if (!is_file($file)) return;

        // extract named segment variables
        if ($segments = $response->segments(true)) {
            extract($segments);
        }

        include_once $file;
        
    }


    public function __get($name) {

        return $this->api($name);

    }

}