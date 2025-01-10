<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;

class TransferFee extends Model
{
    protected $table = 'transfer_fee';
    protected $fillable = ['currency_code', 'fee'];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}
