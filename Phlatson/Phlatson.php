<?php
namespace Phlatson;

/**
 * Root class that ties system together
 * Classes should extend Flatebed when they require access to one or more API variable
 */
class Phlatson
{

    public static $api = [];

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
        } else if(!is_null($name) && is_null($value)) {
            return Api::get($name);
        }
        else {
            return Api::fetchAll();
        }
    }

    /**
     * Runs the request, checks that a Page has been set
     *
     * @param Request $request
     * @return void
     */
    public function execute()
    {
        if ($this->api('page') instanceof Page) {
            return $this->api('page')->render();
        }

    }

}
