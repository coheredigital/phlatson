<?php

class LocalValetDriver extends BasicValetDriver
{
	/**
	 * Determine if the driver serves the request.
	 *
	 * @param  string  $sitePath
	 * @param  string  $siteName
	 * @param  string  $uri
	 * @return bool
	 */
	public function serves($sitePath, $siteName, $uri)
	{
		// print_r('DONE');
		return true;
	}

	/**
	 * Determine if the incoming request is for a static file.
	 *
	 * @param  string  $sitePath
	 * @param  string  $siteName
	 * @param  string  $uri
	 * @return string|false
	 */
	public function isStaticFile($sitePath, $siteName, $uri)
	{
		$staticFilePath = $sitePath . '/' . $uri;

		return file_exists($staticFilePath);
	}


	/**
	 * Get the fully resolved path to the application's front controller.
	 *
	 * @param  string  $sitePath
	 * @param  string  $siteName
	 * @param  string  $uri
	 * @return string
	 */
	public function frontControllerPath($sitePath, $siteName, $uri)
	{
		return $sitePath . '/index.php';
	}
}
