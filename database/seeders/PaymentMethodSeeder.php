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

        // If payment method already exists, skip for all payment methods
        if (!PaymentMethod::where('name', 'PayPal')->first()) {
            PaymentMethod::create(['name' => 'PayPal', 'description' => 'PayPal payment method']);
        }

        if (!PaymentMethod::where('name', 'Credit Card')->first()) {
            PaymentMethod::create(['name' => 'Credit Card', 'description' => 'Pay via Credit Card']);
        }

        if (!PaymentMethod::where('name', 'Bank Transfer')->first()) {
            PaymentMethod::create(['name' => 'Bank Transfer', 'description' => 'Direct Bank Transfer']);
        }
    }
}
