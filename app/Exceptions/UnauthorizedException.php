<?php

namespace DaluPay\Exceptions;

use Exception;
use DaluPay\Http\Controllers\Constants\Messages;

class UnauthorizedException extends Exception
{

	function __construct() {
		parent::__construct(Messages::UNAUTHORIZED, 401);
	}
}
