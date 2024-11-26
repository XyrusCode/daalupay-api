<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DaaluPay\Models\Traits\UuidTrait;

class Wallet extends Model
{
        use UuidTrait;
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory;
    use HasApiTokens;
    use SoftDeletes;

    protected $fillable = [
        'balance',
        'currency',
        'user_id',
    ];

    /**
     * Get the user that owns the wallet.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
