<?php

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
	    		else if($page->template->setting('urlSegments') ) {
	    			$this->request->setMatch($key);
	    			return $page;
	    		}
	    		// otherwise we stop checking
	    		else break;
	    	}

	    }

    }


    public function execute() {
		

    	$match = $this->match($this->request->path);

    	if ($match) {
    		return $match->render();
    	}
	    
        // TODO :  I'd like to see if I can do this without the need for a page and template
        return $this('pages')->get('404')->render();
	    
    
    }


}
