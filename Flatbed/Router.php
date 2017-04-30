<?php
namespace Flatbed;

/*

Working toward using the Router to bind the app together

example: 
    just fixed an issue with every template loading controller, 
    only the current page should execute the controller
    so this is now handled here
    now the problem is how to determine template has extra routes

*/

class Router extends Flatbed
{

    protected $request;
    protected $routes;
    protected $response;
    protected $page;
    protected $template;
    protected $controller;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = new Response;

        // add response to API
        $this->api('response', $this->response);
        
        // $this->page = $this->matchPage($request);
        $this->page = $this->matchPage($request);
        $this->response->page = $this->page;

        if ($this->response->page instanceof Page) {
            $this->controller = new Controller($this->response->page->template);
        }

    }


    // router first matches an exsiting page
    protected function matchPage($request) : ?Page
    {

        // loop through possible URLs
        // checking for an exact page match
        foreach ($request->urls as $key => $url) {
            
            $page = $this('pages')->get($url);
            

            if ($page instanceof Page) {
                // set page API variable
                $this->api('page', $this->page);
                $page->initializeRoutes();

                // return if pge is exct match or has routes defined on it
                if ($key === 0 || $page->routes->count() > 1) {
                    return $page;
                } 

            }
        }

    }


    /**
     * @param Request $request
     * @throws FlatbedException
     *
     */
    public function run(Request $request)
    {
        $found = false;

        foreach ($this->response->page->routes as $route) {
            // continue until a match is made
            if (!$route->match($request)) continue;

            $found = true;

            $this->response = $route->execute( $this->response );
            
            break;
        }

        if (!$found) {
            // TODO :  improve this, a bit to verbose
            $this->response->page = $this('pages')->get('404');
            $this->response->append($this->response->page->render());
            $this->response->code(404);
        }
        
    }


    public function execute()
    {
        $this->run($this->request);
        $this->response->send();
    }
}
