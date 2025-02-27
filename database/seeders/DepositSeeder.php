<?php

namespace Database\Seeders;

use DaaluPay\Models\Deposit;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class DepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 deposits for each user
        $users = User::all();
        foreach ($users as $user) {
            $transaction = Transaction::create([
                'uuid' => Uuid::uuid4(),
                'user_id' => $user->id,
                'amount' => rand(1000000, 10000000),
                'status' => 'completed',
                'reference_number' => Str::random(10),
                'channel' => 'paystack',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Deposit::create([
                'uuid' => Uuid::uuid4(),
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'amount' => rand(1000000, 10000000),
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
