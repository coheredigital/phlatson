<?php

namespace Phlatson;

class View
{
	protected string $path;
	protected string $file;
	protected array $data = [];

	public function __construct(string $root, string $uri)
	{
		// TODO: remove hard coding

		$filepath = $root . '/' . $uri . '.php';

		// validate view file
		if (!file_exists($filepath)) {
			throw new \Exception("Invalid file ($filepath) cannot be used as view");
		}
		$this->file = $filepath;
		$this->path = dirname($filepath);
	}

	public function data(array $data)
	{
		$this->data = $data;
	}

	public function name(): string
	{
		return pathinfo($this->file)['filename'];
	}

	public function renderPartial(?string $url, array $data = []): string
	{
		$url = trim($url, '/');
		$file = "{$this->path}/{$url}.php";
		$output = '';
		$output = $this->renderViewFile($file, $data);

		return $output;
	}

	public function renderSelf(?array $data = []): string
	{
		return $this->renderViewFile($this->file, $data);
	}

	public function renderViewFile(string $file, array $data = []): string
	{
		// merge set data over api
		$data = \array_merge($this->data, $data);

		if (!file_exists($file)) {
			throw new \Exception("View does not exist: $file");
		}

		// render template file
		\ob_start();
		// extract $data array to variables
		\extract($data);

		// render found file
		include $file;

		$output = \ob_get_contents();
		\ob_end_clean();

		return $output;
	}

	public function render(?string $url = null, array $data = []): string
	{
		if ($url) {
			return $this->renderPartial($url, $data);
		}

		return $this->renderSelf($data);
	}
}
