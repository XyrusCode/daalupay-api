<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{

    protected $table = 'withdrawals';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'currency_id',
        'wallet_id',
        'transaction_id',
        'bank_id',
        'bank_name',
        'account_number',
        'account_name',
        'reference',
        'proof_of_payment',
        'note',
        'admin_id',
        'approved_at',
        'declined_at',
        'processed_at',
        'cancelled_at',
        'completed_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the withdrawal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bank account that the withdrawal was made to
     */

    public function bank()
    {
        return $this->belongsTo(UserBankAccount::class, 'bank_id');
    }
}
