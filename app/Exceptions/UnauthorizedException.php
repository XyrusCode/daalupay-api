<?php

namespace DaaluPay\Exceptions;

use Exception;
use DaaluPay\Constants\Messages;

class UnauthorizedException extends Exception
{

	function __construct() {
		parent::__construct(Messages::UNAUTHORIZED, 401);
	}
}
