<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suspension extends BaseModel
{
    use HasFactory;

    protected $table = 'suspensions';

    protected $fillable = [
        'user_id',
        'reason',
        'start_date',
        'end_date',
    ];

    /**
     * Get the user that owns the suspension.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
