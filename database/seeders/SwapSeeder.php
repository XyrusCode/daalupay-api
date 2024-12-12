<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\User;
use DaaluPay\Models\Swap;
use DaaluPay\Models\Transaction;

class SwapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 swap operations for each user
        $users = User::all();
        $transactions = Transaction::all();
        foreach ($users as $user) {
            foreach ($transactions as $transaction) {
                Swap::create([
                    'user_id' => $user->id,
                    'admin_id' => 1,
                    'transaction_id' => $transaction->id,
                    'from_currency' => 'USD',
                    'to_currency' => 'EUR',
                    'from_amount' => 100,
                    'to_amount' => 85,
                    'rate' => 0.85,
                    'status' => 'completed',
                    'notes' => 'Swap operation completed successfully',
                ]);
            }
        }
    }
}
