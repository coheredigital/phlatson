<?php

namespace Phlatson;

/**
 * Root class that ties system together
 * Classes should extend Flatebed when they require access to one or more API variable
 */
class Phlatson
{
    protected $request;
    protected $root;
 
    /**
     * Establishes the data directory to be used for this instance
     *
     * @param string $path
     * @return void
     */
    final public function root(string $path) : void
    {
        // normalize the path
        $path = str_replace(DIRECTORY_SEPARATOR, "/", $path);

        if (!file_exists($path)) {
            throw new Exceptions\PhlatsonException("Path ($path) does not exist, cannot be used as site data");
        }

        $this->$root = $root;
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    final public function api(string $name = null, $value = null, bool $lock = false)
    {
        if (!is_null($name) && !is_null($value)) {
            // all APIs set this way are locked
            // return $this allows chaining
            Api::set($name, $value, $lock);
            return $this;
        } elseif (!is_null($name) && is_null($value)) {
            return Api::get($name);
        } else {
            return Api::fetchAll();
        }
    }

    /**
     * Runs the request, checks that a Page has been set
     *
     * @param Request $request
     * @return void
     */
    public function execute(Request $request)
    {

        $this->request = $request;

        // determine the requested page
        $page = new Page($request->url);
        $template = $page->template;
        $view = $template->view;

        $this->api('page', $page);
        $this->api('template', $template);
        $this->api('view', $view);

        if ($view instanceof View) {
            return $view->render();
        }
    }


    public function route(string $path, $callback)
    {
        # code...
    }

}
