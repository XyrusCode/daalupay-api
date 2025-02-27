<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *  type="object",
 *
 *  @OA\Property(
 *    type="number",
 *    property="balance",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="currency",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="user_id",
 *  ),
 * )
 *
 * @property number $balance
 * @property string $currency
 * @property string $user_id
 */
class Wallet extends Model
{
    use HasApiTokens;

    //
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'wallets';

    protected $fillable = [
        'uuid',
        'balance',
        'currency',
        'user_id',
        'currency_id',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the deposits for the wallet.
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class, 'wallet_id', 'id');
    }
}
