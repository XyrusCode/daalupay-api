<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DaaluPay\Models\Traits\UuidTrait;

/**
 * @OA\Schema(
 *  type="object",
 *  @OA\Property(
 *    type="string",
 *    property="id",
 *  ),
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
 * @property string $id
 * @property number $balance
 * @property string $currency
 * @property string $user_id
 */
class Wallet extends Model
{
        // use UuidTrait;
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory;
    use HasApiTokens;
    use SoftDeletes;

    protected $table = 'wallets';

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
