<?php

namespace Phlatson;

class DataFile extends File
{
	protected array $data;

	public function __construct(string $file, ?DataFolder $folder = null)
	{
		// setup base object
		parent::__construct($file, $folder);

		// import data
		$this->data = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);

		// check that we got data back from json_decode
		if (null === $this->data) {
			throw new \Exception("File ($file) is not a valid JSON file");
		}
	}

	/**
	 * Gets and returns a key from the data array.
	 *
	 * @return Mixed
	 */
	public function get(string $key)
	{
		switch ($key) {
			default:
				return $this->data[$key] ?? null;
				break;
		}
	}

	/**
	 * Set the $key in $data array to the supplied $value.
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 */
	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function data(): array
	{
		return $this->data;
	}

	public function merge(DataFile $json)
	{
		$this->data = array_replace_recursive($this->data, $json->data());
	}

	public function save()
	{
		$json = json_encode($this->data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
		file_put_contents($this->file, $json);
	}
}
