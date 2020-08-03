<?php

namespace Phlatson;

class PageFinder
{
	// TODO: placeholder
	protected DataFolder $data;

	public function __construct(DataFolder $data)
	{
		$this->data = $data;
	}

	public function get($uri): Page
	{
		$datafile = $this->data->get($uri);
		// code...
		$page = new Page($datafile);
	}
}
