<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\WalletSeeder;
use Database\Seeders\TransactionSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\CountryCurrencySeeder;
use Database\Seeders\SwapSeeder;
use Database\Seeders\DepositSeeder;
use Database\Seeders\ExchangeRateSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CountryCurrencySeeder::class,
            ExchangeRateSeeder::class,
            PaymentMethodSeeder::class,
            UserSeeder::class,
            WalletSeeder::class,

            DepositSeeder::class,
            TransactionSeeder::class,
            SwapSeeder::class,
        ]);
    }
}
