<?php

namespace DaaluPay\Exceptions;

use DaaluPay\Constants\Messages;
use Exception;

class UnauthorizedException extends Exception
{
    public function __construct()
    {
        parent::__construct(Messages::UNAUTHORIZED, 401);
    }
}
