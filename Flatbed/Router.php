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

        $this->page = $this->matchPage($request->path);

        if ($this->page instanceof Page) {
            $this->template = $this->page->template;
            $this->controller = new Controller($this->template);
        }

    }


    // router first matches an exsiting page
    protected function matchPage($path)
    {

        // loop through possible URLs
        // checking for an exact page match
        foreach ($this->request->urls as $key => $url) {
            
            $page = $this('pages')->get($url);
           
            

            if ($page instanceof Page) {

                $page->initializeRoutes();

                // page match on first url is an exact match, return it
                if ($key === 0) {
                    return $page;
                } //  
                else if ($page->routes->count() > 1) {
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
    public function getResponse(Request $request)
    {
        $found = false;

        foreach ($this->page->routes as $route) {
            if (!$route->match($request)) continue;
            $route->execute();
            $found = true;
            break;
        }

        if (!$found) {
            d("route no matchy");
        }
        
    }


    public function execute()
    {

        // TEMP
        $this->getResponse($this->request);

        if ($this->page instanceof Page) {
            $this->api('page', $this->page, true);
        } else {
            // TODO :  I'd like to see if I can do this without the need for a page and template
            $this->page = $this('pages')->get('404');
            $this->response->code(404);
        }

        // add response and page to api
        $this->response->append( $this->page->render() );
        $this->response->send();
    }
}
