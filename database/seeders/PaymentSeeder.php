<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\Payment;
use DaaluPay\Models\User;
class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            Payment::create([
                'name' => 'New Payment',
                'amount' => 100,
                'method' => 'PayPal',
                'type' => 'deposit',
                'channel' => 'online',
                'status' => 'pending',
            ]);
        }
    }
}
