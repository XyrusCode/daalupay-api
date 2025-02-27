<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends BaseModel
{
    use HasFactory;

    protected $table = 'transactions';

    protected $hidden = [
        'id',
        'updated_at',

    ];

    protected $fillable = [
        'uuid',
        'reference_number',
        'channel',
        'amount',
        'type',
        'status',
        'user_id',
        'admin_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user associated with the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
