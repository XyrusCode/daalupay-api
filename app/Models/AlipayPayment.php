<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlipayPayment extends Model
{
    protected $table = 'alipay_payment';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'recipient_alipay_id',
        'recipient_name',
        'recipient_email',
        'description',
        'transaction_id',
        'document_type',
        'proof_of_payment',
    ];

    /**
     * Get the user that owns the AlipayPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
