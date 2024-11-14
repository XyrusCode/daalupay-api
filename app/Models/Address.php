<?php

namespace DaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DaluPay\Models\BaseModel;
use DaluPay\Models\User;

class Address extends BaseModel
{
    use HasFactory;

    protected $table = 'addresses';

    /** @var array<int, string> */
    protected $fillable = [
        'user_id',
        'town',
        'state',
        'country',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
