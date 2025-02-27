<?php

namespace Database\Seeders;

use DaaluPay\Models\Currency;
use DaaluPay\Models\ExchangeRate;
use Illuminate\Database\Seeder;

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
                'rate' => rand(1, 2000),
            ]);
        }
    }
}
