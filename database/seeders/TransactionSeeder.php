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
        // Create 5 transactions for each user
        $users = User::all();
        $admins = Admin::all();
        foreach ($users as $user) {
            Transaction::create([
                'uuid' => Uuid::uuid4(),
                'user_id' => $user->id,
                'amount' => rand(1000000, 10000000),
                'status' => 'completed',
                'reference_number' => Str::random(10),
                'channel' => 'paystack',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

