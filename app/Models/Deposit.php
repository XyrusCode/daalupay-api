<?php

namespace DaaluPay\Models;

use DaaluPay\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use DaaluPay\Models\Wallet;
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
        'wallet_id',
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
        return $this->hasOne(Transaction::class, 'payment_id', 'id');
    }

    /**
     * Get the wallet that the person is depositing to
     *
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
