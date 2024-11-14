<?php

namespace DaluPay\Exceptions;

use Exception;
use DaluPay\Constants\Messages;

class NotFoundException extends Exception
{

	function __construct(string $resourceName) {
		parent::__construct(sprintf(Messages::NOT_FOUND, $resourceName), 404);
	}
}
