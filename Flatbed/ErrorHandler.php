<?php
namespace Flatbed;

class ErrorHandler {

	private $isRegistered;

	public function __construct() {
		set_error_handler([
			$this,
			'error'
		]);

		set_exception_handler([
			$this,
			'throw'
		]);
	}

	static function error($errno, $errorMessage, $errfile, $errline) {
		if (!(error_reporting() & $errno)) {
			// This error code is not included in error_reporting, so let it fall
			// through to the standard PHP error handler
			return false;
		}

		switch ($errno) {
			case E_USER_ERROR:
			case E_RECOVERABLE_ERROR:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_STRICT:
			case E_ERROR:
			case E_PARSE:
			case E_PARSE:
				throw new FlatbedException($errorMessage, $errno);
				break;

			default:
				// var_dump($errno);
				// throw new Exceptions\FlatbedException($errorMessage, $errno);
				break;
		}
		return true;
	}

	static function throw($errorMessage) {
		throw new Exceptions\FlatbedException($errorMessage, $errno);
	}

}
