<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\WalletSeeder;
use Database\Seeders\TransactionSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\CountryCurrencySeeder;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\SwapSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // PaymentMethodSeeder::class,
            // CountryCurrencySeeder::class,
            WalletSeeder::class,
            // UserSeeder::class,
            // AdminSeeder::class,
            //  PaymentSeeder::class,
            // TransactionSeeder::class,
            // SwapSeeder::class

        ]);
    }
}
