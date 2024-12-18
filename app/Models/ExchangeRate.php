<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{

    protected $table = 'exchange_rate';
    protected $fillable = ['from_currency', 'to_currency', 'rate'];

    protected $primaryKey = 'uuid';
}
