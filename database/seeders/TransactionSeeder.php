<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\User;
use DaaluPay\Models\Payment;
USE DaaluPay\Models\Admin;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 transactions for each user
        $users = User::all();
        $payments = Payment::all();
        $admins = Admin::all();
        foreach ($users as $user) {
            foreach ($payments as $payment) {
                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => rand(1000000, 10000000),
                    'send_currency' => 1,
                    'receive_currency' => 2,
                    'status' => 'completed',
                    'transaction_date' => now(),
                    'reference_number' => Str::random(10),
                    'payment_id' => $payment->id,
                    'channel' => 'web',
                    'fee' => 0,
                    'rate' => 1,
                    'admin_id' => $admins->random()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
