<?php

namespace DaluPay\Exceptions;

use Exception;
use DaluPay\Constants\Messages;

class UnauthorizedException extends Exception
{

	function __construct() {
		parent::__construct(Messages::UNAUTHORIZED, 401);
	}
}
