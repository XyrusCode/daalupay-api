<?php

namespace DaaluPay\Helpers;

abstract class StatusCodeHelper
{
    public static function isSuccess(int|string $code): bool
    {
        return $code >= 200 && $code <= 299;
    }

    public static function isRedirect(int|string $code): bool
    {
        return in_array($code, [301, 302, 303, 307, 308]);
    }

    public static function isValid(int|string $code): bool
    {
        return is_int($code) && $code >= 100 && $code <= 599;
    }
}
