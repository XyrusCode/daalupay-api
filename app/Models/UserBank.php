<?php

namespace DaaluPay\Models;

use OpenApi\Annotations as OA;

class UserBank extends BaseModel
{
    protected $table = 'user_bank';

    protected $fillable = [
        'account_number',
        'name',
        'user_id'
    ];

    protected $visible = [
        'account_number',
        'name'
    ];

    protected $validationRules = [
        'account_number' => [
            'rules' => [
                'required'
            ]
        ],
        'name' => [
            'rules' => [
                'required'
            ]
        ]
    ];
}
