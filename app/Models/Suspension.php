<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suspension extends BaseModel
{
    use HasFactory;

    protected $table = 'suspensions';

    protected $fillable = [
        'uuid',
        'user_id',
        'admin_id',
        'status',
        'reason',
        'reactivation_reason',
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

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
