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
    protected $routes;

    final public function __construct(Response $response)
    {

        // create the routes collection
        $this->routes = new RouteCollection;

        // TODO :  this should not be needed here, temp fix
        $this->response = $response;

        // determine controller file
        $name = $response->template->name;
		$method = $this->request->method;

        // determine root path based on isSystem() return value
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

        // and include controller
        include_once $file;
    }

    public function respond($path = '*', Callable  $callback = null)
    {

        $method = 'GET';

        if(strpos($path, ':') !== false) {
            
            list($method, $path) = explode(":",$path); 

        }

        // prepend current page to path
        $path = rtrim($this->response->page->url, "/") . $path;

        // $route = $this->route_factory->build($callback, $path, $method);
        $route = new Route([
            "method" => $method,
            "path" => $path,
            "callback" => $callback
        ]);
        $this->routes->append($route);
        return $route;
    }

    /**
     * @param Request $request
     * @throws FlatbedException
     *
     */
    public function run(Request $request)
    {
        $found = false;

        foreach ($this->routes as $route) {
            if (!$route->match($request)) continue;
            $route->execute();
            $found = true;
            break;
        }

        if (!$found) {
            d("route no matchy");
        }
        
    }

    // give property access to variable
    public function __get($name) {
        return $this->api($name);
    }

}