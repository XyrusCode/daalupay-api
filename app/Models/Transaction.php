<?php

namespace DaaluPay\Models;

use DaaluPay\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use DaaluPay\Models\Payment;
use DaaluPay\Models\Employee;
use DaaluPay\Models\User;

/**
 * @OA\Schema(
 *  type="object",
 *  @OA\Property(
 *    type="string",
 *    property="reference_number",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="channel",
 *  ),
 *  @OA\Property(
 *    type="number",
 *    property="amount",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="send_currency",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="receive_currency",
 *  ),
 *  @OA\Property(
 *    type="number",
 *    property="rate",
 *  ),
 *  @OA\Property(
 *    type="number",
 *    property="fee",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="transaction_date",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="status",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="user_id",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="admin_id",
 *  ),
 *  @OA\Property(
 *    type="string",
 *    property="payment_id",
 *  )
 * )
 * @property string $reference_number
 * @property string $channel
 * @property number $amount
 * @property string $send_currency
 * @property string $receive_currency
 * @property number $rate
 * @property number $fee
 * @property string $transaction_date
 * @property string $status
 * @property string $user_id
 * @property string $admin_id
 */
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
        'updated_at'
    ];

    /**
     * Get the user associated with the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
