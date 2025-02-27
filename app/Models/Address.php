<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
