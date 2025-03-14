<?php

namespace Daalupay\Models;

use DaaluPay\Models\BaseModel;
use DaaluPay\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends BaseModel
{
    use HasFactory;

    protected $table = 'user_preferences';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'notify_email',
        'notify_sms',
        'theme',
        'daily_transaction_limit',
        'transaction_total_today',
        'last_transaction_date',
        'kyc_status',
        'two_fa_enabled',
    ];

    protected $casts = [
        'last_transaction_date' => 'datetime',
    ];

    /**
     * Relationship: each preference record belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
