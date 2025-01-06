<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\User;
use DaaluPay\Models\Payment;
USE DaaluPay\Models\Admin;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trasnaction_types = ['deposit', 'withdrawal', 'swap'];
        $channels = ['paystack', 'flutterwave', 'alipay', 'paypal'];
        $statuses = ['pending', 'completed', 'failed'];
        // Create 5 transactions for each user
        $users = User::all();
        $admins = Admin::all();
        foreach ($users as $user) {
            Transaction::create([
                'uuid' => Uuid::uuid4(),
                'user_id' => $user->id,
                'amount' => rand(1000000, 10000000),
                'status' => $statuses[array_rand($statuses)],
                'type' => $trasnaction_types[array_rand($trasnaction_types)],
                'reference_number' => Str::random(10),
                'channel' => $channels[array_rand($channels)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

