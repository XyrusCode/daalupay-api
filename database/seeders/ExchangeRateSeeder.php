<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\ExchangeRate;
use DaaluPay\Models\Currency;
use Ramsey\Uuid\Uuid;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = Currency::all();
        foreach ($currencies as $currency) {
            ExchangeRate::create([
                'from_currency' => $currency->code,
                'to_currency' => 'USD',
                'rate' => 1,
            ]);
        }
    }
}
