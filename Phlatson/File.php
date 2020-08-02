<?php

namespace Phlatson;

class File
{

	protected string $file;
	protected string $name;
	protected string $path;
	protected string $url;

	public function __construct(string $file, ?Page $page = null)
	{

		// TODO : throw Exception if not valid file
		$this->file = $file;
		$this->filesizeFormatted = $this->formatSizeUnits($this->filesize);
		$this->name = \basename($file);
		$this->extension = pathinfo($name, PATHINFO_EXTENSION);

		// page dependant parameters
		if (isset($page)) {
			$this->url = $page->url . $page->uri . "/" . rawurlencode($name);
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
