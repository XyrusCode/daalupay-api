<?php

namespace DaaluPay\Models;

class ActivityLog extends BaseModel
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'data',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    // Hide sensitive fields
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
}
