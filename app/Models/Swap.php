<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DaaluPay\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DaaluPay\Models\User;
use DaaluPay\Models\Admin;
use DaaluPay\Models\Transaction;

class Swap extends Model
{
  use HasFactory;
//   use UuidTrait;

    // Define the table associated with the model
    protected $table = 'swap_operations';

    protected $fillable = [
        'user_id',
        'from_currency',
        'to_currency',
        'from_amount',
        'to_amount',
        'rate',
        'status',
        'admin_id',
        'transaction_id',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
