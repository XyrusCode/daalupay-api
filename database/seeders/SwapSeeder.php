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

        $swapStatuses = ['pending', 'approved', 'rejected'];
        // Create 5 swap operations for each user
        $users = User::all();
        $admins = Admin::all();
        $transactions = Transaction::all();
        foreach ($users as $user) {
            foreach ($transactions as $transaction) {
                Swap::create([
                    'uuid' => Uuid::uuid4(),
                    'user_id' => $user->id,
                    'admin_id' => $admins->random()->id,
                    'transaction_id' => $transaction->id,
                    'from_currency' => 'USD',
                    'to_currency' => 'EUR',
                    'from_amount' => 100,
                    'to_amount' => 85,
                    'rate' => 0.85,
                    'status' => $swapStatuses[array_rand($swapStatuses)],
                    'notes' => 'Swap operation completed successfully',
                ]);
            }
        }
    }
}
