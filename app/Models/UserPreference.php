<?php

namespace Daalupay\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    // Define the table if it doesn't follow Laravel's naming conventions.
    protected $table = 'user_preferences';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'notify_email',
        'notify_sms',
        'notify_push',
        'theme',
        'daily_transaction_limit',
        'transaction_total_today',
        'last_transaction_date',
        'kyc_status',
        'two_fa_enabled',
    ];

    /**
     * Relationship: each preference record belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
