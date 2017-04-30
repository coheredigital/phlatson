<?php
namespace Flatbed;


class RouteCollection implements \IteratorAggregate, \Countable
{

    protected $page;
    protected $template;
    protected $data = [];

    public function __construct( Page $page )
    {
            $this->page = $page;

            // determine root path based on isSystem() return value
            if ($page->template->isSystem()) {
                $path = SYSTEM_PATH . "routes" . DIRECTORY_SEPARATOR;
            }
            else {
                $path = SITE_PATH . "routes" . DIRECTORY_SEPARATOR;
            }

            
            $file = "{$path}{$page->template->name}.php";
            
            // no controller file was found, return
            if (!is_file($file)) return;

            // and include route definition file
            include_once $file;
        

    }


    public function append(Route $route) : self
    {
        $this->data[] = $route;
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

    
    public function respond(string $path, Callable  $callback = null)
    {

        $method = 'GET';

        if(strpos($path, ':') !== false) {
            
            list($method, $path) = explode(":",$path); 

        }

        // prepend current page to path
        $path = rtrim($this->page->url, "/") . $path;

        // $route = $this->route_factory->build($callback, $path, $method);
        $route = new Route([
            "method" => $method,
            "path" => $path,
            "callback" => $callback
        ]);

        $this->append($route);
        return $route;
    }




}
