<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
