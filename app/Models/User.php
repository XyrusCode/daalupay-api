<?php

namespace DaaluPay\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DaaluPay\Models\Address;
use DaaluPay\Models\KYC;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *  type="object",
 *  @OA\Property(
 *    type="string",
 *    property="id",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="first_name",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="last_name",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="email",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="phone",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="password",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="status",
 *  ),
 *  @OA\Property(
 *    type="array",
 *    property="wallets",
 *    @OA\Items(type="object", ref="#/components/schemas/Wallet")
 *  ),
 *  @OA\Property(
 *    type="array",
 *    property="transactions",
 *    @OA\Items(type="object", ref="#/components/schemas/Transaction")
 *  )
 * )
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $status
 * @property array $wallets
 * @property array $transactions
 */
class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'gender',
        'email',
        'phone',
        'password',
        'status',
        'kyc_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the address associated with the user.
     *
     * @return HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the wallet associated with the user.
     *
     * @return HasMany
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Get the transactions for the user.
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the deposits for the user.
     *
     * @return HasMany
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get the swaps for the user.
     *
     * @return HasMany
     */
    public function swaps(): HasMany
    {
        return $this->hasMany(Swap::class);
    }


    /**
     * Get the KYC associated with the user.
     *
     * @return HasOne
     */
    public function kyc(): HasOne
    {
        return $this->hasOne(KYC::class);
    }
}
