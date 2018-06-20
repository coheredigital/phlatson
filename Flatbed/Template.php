<?php
namespace Flatbed;
class Template extends FlatbedObject
{

    const DATA_FOLDER = 'templates';

    public $parent; // the object this template belongs to
    public $defaultFields = ['title','fields', 'name','view','modified'];
    

    public function hasField($name){
        
        return isset($this->data["fields"][$name]);
    }

    public function addField( string $name )
    {

        if (!$field = $this->api('fields')->get($name)) {
            return;
        }

        return $this->data["fields"][$name];
    }

    public function get( string $name)
    {
        switch ($name) {
            case 'template':
                $template = $this->api('templates')->get('template');
                $template->parent = $this;
                return $this->api('templates')->get('template');
            case 'view':
                // TODO : look into allowing this to be configurable
                return $this('views')->get($this->name);
            case 'objectType': // protected / private variable that should have public get
                return $this->{$name};
            default:
                return parent::get($name);
        }

    }


    public function respond(string $path, Callable  $callback = null)
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


}
