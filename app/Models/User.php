<?php

namespace DaaluPay\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *  type="object",
 *
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
 *
 *    @OA\Items(type="object", ref="#/components/schemas/Wallet")
 *  ),
 *
 *  @OA\Property(
 *    type="array",
 *    property="transactions",
 *
 *    @OA\Items(type="object", ref="#/components/schemas/Transaction")
 *  )
 * )
 *
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
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;
    use Notifiable;
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
        'pin',
        'status',
        'kyc_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pin',
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
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the wallet associated with the user.
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the deposits for the user.
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get the withdrawals for the user.
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Get the bank accounts for the user.
     */
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(UserBankAccount::class);
    }

    /**
     * Get the swaps for the user.
     */
    public function swaps(): HasMany
    {
        return $this->hasMany(Swap::class);
    }

    /**
     * Get the KYC associated with the user.
     */
    public function kyc(): HasOne
    {
        return $this->hasOne(KYC::class);
    }

    public function notificationTokens(): HasMany
    {
        return $this->hasMany(NotificationToken::class);
    }

    /**
     * A user can have many messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * A user can have one preference
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }
}
