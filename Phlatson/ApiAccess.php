<?php

namespace Phlatson;

trait ApiAccess
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


	final public function classname() : string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
