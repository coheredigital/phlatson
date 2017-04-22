<?php
namespace Flatbed;

final class ExtensionStub extends Object {


	public function instantiate() {
		$extension = new $this->name($this->file);
	}

}