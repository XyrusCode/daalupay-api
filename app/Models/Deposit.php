<?php

namespace DaaluPay\Models;

use DaaluPay\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'transaction_id',
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
     * Get the transaction for the deposit.
     *
     * @return  HasOne
     */
    public function transaction(): HasOne
    {
        return $this->belongsTo(Transaction::class, 'payment_id', 'id');
    }
}
