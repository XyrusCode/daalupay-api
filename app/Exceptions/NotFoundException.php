<?php

namespace DaluPay\Exceptions;

use Exception;
use DaluPay\Http\Controllers\Constants\Messages;

class NotFoundException extends Exception
{

	function __construct(string $resourceName) {
		parent::__construct(sprintf(Messages::NOT_FOUND, $resourceName), 404);
	}
}
