<?php
namespace Flatbed;

/**
 * Root class that ties system together
 * Classes should extend Flatebed when they require access to one or more API variable
 */
class Flatbed
{


    protected $request;
    protected $page;

    public function init()
    {
        $this->request = new Request;
        r($this->request->url);
        $this->page = new Page($this->request->url);
    }

    /**
     * Runs the request 
     *
     * @param Request $request
     * @return void
     */
    public function execute()
    {
        r($this);
    }

}
