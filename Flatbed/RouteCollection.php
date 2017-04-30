<?php
namespace Flatbed;


class RouteCollection extends Flatbed implements \IteratorAggregate, \Countable
{

    protected $page;
    protected $template;
    protected $data = [];

    public function __construct( Page $page )
    {
        $this->page = $page;

        // setup default route
        $this->respond( "/" , function( $response ){
            $response->append(  $response->page->render() );
        });

        // determine root path based on isSystem() return value
        if ($page->template->isSystem()) $path = SYSTEM_PATH . "routes" . DIRECTORY_SEPARATOR;
        else $path = SITE_PATH . "routes" . DIRECTORY_SEPARATOR;
        
        // default template routs file location
        $file = "{$path}{$page->template->name}.php";
        // no controller file was found, return
        if (is_file($file)) include_once $file;            
        
    }


    public function append(Route $route) : self
    {
        $this->data[$route->name] = $route;
        return $this;
    }

    /* Interface requirements */
    public function getIterator()
    {
        return new \ArrayObject($this->data);
    }

    public function count()
    {
        return count($this->data);
    }

    
    public function respond(?string $name = null, Callable $callback = null)
    {

        $method = 'GET';

        if(strpos($name, ':') !== false) {
            
            list($method, $path) = explode(":",$path); 

        }
        else {
            $path = $name;
        }

        // prepend current page to path
        $path = rtrim($this->page->url, "/") . $path;

        // $route = $this->route_factory->build($callback, $path, $method);
        $route = new Route([
            "name" => $name,
            "method" => $method,
            "path" => $path,
            "callback" => $callback
        ]);

        $this->append($route);
        return $route;
    }

}