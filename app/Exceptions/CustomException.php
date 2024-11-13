<?php

namespace DaluPay\Exceptions;

use Exception;

class CustomException extends Exception
{

	function __construct(string $message, int $code = 500) {
		parent::__construct($message, $code);
	}
}
