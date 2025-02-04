<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationToken extends Model
{
    protected $table = 'device_token';

    protected $fillable = [
        'token',
        'user_id',
        'status',
        'device_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
