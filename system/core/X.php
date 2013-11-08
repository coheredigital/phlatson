<?php

abstract class X {





	/**
	 * Return this object's class name
	 *
	 * Note that it caches the class name in the $className object property to reduce overhead from calls to get_class().
	 *
	 * @return string
	 *
	 */
	public function className() {
		if(!$this->className) $this->className = get_class($this);
		return $this->className;
	}



}

