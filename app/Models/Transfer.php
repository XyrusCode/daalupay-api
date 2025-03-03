<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    protected $table = 'transfers';

    protected $fillable = [
        'user_id',
        'admin_id',
        'amount',
        'currency',
        'status',
        'payment_details',
        'recipient_name',
        'recipient_email',
        'description',
        'transaction_id',
        'document_type',
        'proof_of_payment',
    ];

    /**
     * Get the user that owns the Transfer
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
