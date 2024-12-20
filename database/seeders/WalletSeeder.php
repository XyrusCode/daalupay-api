<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DaaluPay\Models\Currency;
use DaaluPay\Models\User;
use DaaluPay\Models\Wallet;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencyCode = 'NGN';
        $currencyId = DB::table('currencies')->where('code', $currencyCode)->first()->id;

        $users = User::all();
        foreach ($users as $user) {
            Wallet::create([

                'uuid' => Uuid::uuid4(),
                'user_id' => $user->id,
                'currency_id' => $currencyId,
                'balance' => rand(1000000, 10000000),
            ]);
        }

    }
}
