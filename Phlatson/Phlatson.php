<?php

namespace Phlatson;

/**
 * Root class that ties system together
 *      - Gives access to the internal Api
 *      - Allow for extending of & addition to class methods
 */

class Phlatson
{    

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




    final public function classname(bool $full = false)
    {
        if ($full) {
            return __CLASS__;
        }
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Runs the request, checks that a Page has been set
     *
     * @param Request $request
     */
    public function execute(Request $request)
    {

        $this->request = $request;

        // determine the requested page
        $url = $request->url;
        $page = new Page($url);
        $template = $page->template;
        $view = $template->view;

        $this->api('page', $page);
        $this->api('template', $template);
        $this->api('view', $view);

        if ($view instanceof View) {
            return $view->render();
        }
    }

}
