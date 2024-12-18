<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\User;
use DaaluPay\Models\Swap;
use DaaluPay\Models\Admin;
use DaaluPay\Models\Transaction;
use Ramsey\Uuid\Uuid;
class SwapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 swap operations for each user
        $users = User::all();
        $admins = Admin::all();
        $transactions = Transaction::all();
        foreach ($users as $user) {
            foreach ($transactions as $transaction) {
                Swap::create([
                    'uuid' => Uuid::uuid4(),
                    'user_id' => $user->uuid,
                    'admin_id' => $admins->random()->uuid,
                    'transaction_id' => $transaction->uuid,
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
