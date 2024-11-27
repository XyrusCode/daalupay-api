<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DaaluPay\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        PaymentMethod::create(['name' => 'PayPal', 'description' => 'PayPal payment method']);
        PaymentMethod::create(['name' => 'Credit Card', 'description' => 'Pay via Credit Card']);
        PaymentMethod::create(['name' => 'Bank Transfer', 'description' => 'Direct Bank Transfer']);
    }
}
