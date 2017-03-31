<?php


final class ExtensionStub extends Object {


	public function initialize() {

		$extension = new $this->name($this->file);

	}

}