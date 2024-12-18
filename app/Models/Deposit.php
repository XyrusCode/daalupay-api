<?php

namespace DaaluPay\Models;

use DaaluPay\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends BaseModel
{
    use HasFactory;


    protected $hidden = [
        'uuid',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'uuid',
        'amount',
        'status',
        'user_id',
        'payment_id',
        'channel',
    ];

    /**
     * Get the user that owns the deposit.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the payment that owns the deposit.
     *
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
