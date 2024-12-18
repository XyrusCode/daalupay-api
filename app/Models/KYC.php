<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KYC extends Model
{
    protected $table = 'kyc';

    protected $fillable = ['user_id', 'status', 'type', 'document_type', 'document_number', 'document_image'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
