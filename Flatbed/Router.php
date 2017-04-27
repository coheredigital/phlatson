<?php
namespace Flatbed;
class Router extends Flatbed
{

    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    protected function match($path) {

    	// check for an exact page match
	    foreach ($this->request->urls as $key => $url) {
	    	
	    	$page = $this('pages')->get($url);
	    	
	    	if ($page) {
	    		// page match on first url is an exact match, return it
	    		if ($key === 0) {
	    			$this->request->setMatch($key);
	    			return $page;
	    		}
	    		// subsequent match is a partial match, and requires template to support url segments
	    		else if($page->template->setting('allow_segments') ) {
	    			$this->request->setMatch($key);
	    			return $page;
	    		}
	    		// otherwise we stop checking
	    		else break;
	    	}

	    }

    }


    public function execute() {
		
		

    	$page = $this->match($this->request->path);

    	if ($page instanceof Page) {

    		$this->api('page', $page, true);
    		// create reponse and add to API
    		$response = new Response($this->request, $page);
    		$this->api('response', $response, true);


			$controllerFile = $this->getControllerFile($response);

			
			$controller = new class($response) extends Controller {

			};
			

			// execute controller
			
			// $controller->execute($controllerFile, $response);
			// (new Controller($response));

    	}
    	else {
	        // TODO :  I'd like to see if I can do this without the need for a page and template
	        $page = $this('pages')->get('404');

	        // set the response so the page has access
	        $response = new Response($this->request, $page);
	        $response->code(404);
	        $this->api('response', $response, true);

	        
	        
    	}

    	// add response and page to api
    	$response->append( $page->render() );
	    $response->send();
    
    }


	public function getControllerFile($response) {

		$template = $response->template;

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


}
