<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DaaluPay\Models\Currency;
use DaaluPay\Models\User;
use DaaluPay\Models\Wallet;
use Illuminate\Support\Str;
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
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'currency_id' => $currencyId,
                'balance' => Str::random(10),
            ]);
        }

    }
}
