<?php

namespace DaaluPay\Constants;

class ErrorCodes
{
    final const ACCOUNT_PENDING = 'ACCOUNT_PENDING'; // Account is Pending Activation on Login

    final const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS'; // Invalid Credentials

    final const INVALID_TRANSACTION_STATUS = 'INVALID_TRANSACTION_STATUS'; // Invalid Transaction Status

    final const INVALID_PAYMENT_METHOD = 'INVALID_PAYMENT_METHOD'; // Invalid Payment Method

    final const INVALID_PAYMENT_AMOUNT = 'INVALID_PAYMENT_AMOUNT'; // Invalid Payment Amount

    final const INVALID_PAYMENT_CURRENCY = 'INVALID_PAYMENT_CURRENCY'; // Invalid Payment Currency

    final const INVALID_PAYMENT_REFERENCE = 'INVALID_PAYMENT_REFERENCE'; // Invalid Payment Reference
}
