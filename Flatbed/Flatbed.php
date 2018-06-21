<?php
namespace Flatbed;
namespace Flatbed;

/**
 * Root class that ties system together
 * Classes should extend Flatebed when they require access to one or more API variable
 */
class Flatbed
{


    protected $request;

    public function init(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Runs the request 
     *
     * @param Request $request
     * @return void
     */
    public function execute()
    {
        // r();
    }

}
