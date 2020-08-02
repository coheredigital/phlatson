<?php

namespace Phlatson;

class File
{

	protected string $file;
	protected string $path;
	protected string $path;


	public function __construct(string $file, ?Page $page = null)
	{

		// TODO : throw PhlatsonException if not valid file
		$this->file = $file;


		// $this->url = $this->api('pages')->url . $page->uri . "/" . rawurlencode($name);
		$this->file = $page->path . $name;

		$this->filesizeFormatted = $this->formatSizeUnits($this->filesize);
		$this->name = $name;
		$this->extension = pathinfo($name, PATHINFO_EXTENSION);

		//
		if (isset($page)) {
			$this->page = $page->url;
			$this->path = $page->path;
		}
	}

	public function filesize(): int
	{
		return filesize($this->file);
	}

	public function filesizeStr(): int
	{

		$bytes = $this->filesize();

		if ($bytes >= 1073741824) {
			return number_format($bytes / 1073741824, 1) . ' GiB';
		} elseif ($bytes >= 104857) {
			return number_format($bytes / 1048576, 1) . ' MiB';
		} elseif ($bytes >= 1024) {
			return number_format($bytes / 1024, 1) . ' KiB';
		} elseif ($bytes > 1) {
			return $bytes . ' bytes';
		} elseif ($bytes == 1) {
			return $bytes . ' byte';
		} else {
			return '0 bytes';
		}
	}

	protected function formatSizeUnits($bytes)
	{
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 1) . ' GiB';
		} elseif ($bytes >= 104857) {
			$bytes = number_format($bytes / 1048576, 1) . ' MiB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 1) . ' KiB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	}


	public function get(string $string)
	{
		switch ($string) {
			case 'directory':
				if (is_null($this->name)) {
					$lastRequestIndex = count($this->route) - 1;
					$this->name = $this->route[$lastRequestIndex];
				}
				return $this->name;

			default:
				return $this->data[$string];
				break;
		}
	}
}
