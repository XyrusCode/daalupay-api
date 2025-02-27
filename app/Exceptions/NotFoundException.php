<?php

namespace DaaluPay\Exceptions;

use DaaluPay\Constants\Messages;
use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $resourceName)
    {
        parent::__construct(sprintf(Messages::NOT_FOUND, $resourceName), 404);
    }
}
