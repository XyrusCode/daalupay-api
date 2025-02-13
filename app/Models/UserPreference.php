<?php

namespace Daalupay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DaaluPay\Models\BaseModel;
use DaaluPay\Models\User;

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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
