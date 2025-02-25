<?php

namespace DaaluPay\Models;

use OpenApi\Annotations as OA;

class UserBankAccount extends BaseModel
{
    protected $table = 'user_bank_accounts';

    protected $fillable = [
        'user_id',
        'account_number',
        'account_name',
        'bank_name'

    ];

    protected $validationRules = [
        'account_number' => [
            'rules' => [
                'required'
            ]
        ],
        'bank_name' => [
            'rules' => [
                'required'
            ]
        ]
    ];
}
