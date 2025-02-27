<?php

namespace Database\Seeders;

use DaaluPay\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // If payment method already exists, skip for all payment methods
        if (! PaymentMethod::where('name', 'PayPal')->first()) {
            PaymentMethod::create([
                'name' => 'PayPal',
                'description' => 'PayPal payment method',
            ]);
        }

        if (! PaymentMethod::where('name', 'Alipay')->first()) {
            PaymentMethod::create([
                'name' => 'Alipay',
                'description' => 'Alipay payment method',
            ]);
        }

        if (! PaymentMethod::where('name', 'Bank Transfer')->first()) {
            PaymentMethod::create([
                'name' => 'Bank Transfer',
                'description' => 'Direct Bank Transfer',
            ]);
        }

        if (! PaymentMethod::where('name', 'Paystack')->first()) {
            PaymentMethod::create([
                'name' => 'Paystack',
                'description' => 'Paystack payment method',
            ]);
        }

        if (! PaymentMethod::where('name', 'Flutterwave')->first()) {
            PaymentMethod::create([
                'name' => 'Flutterwave',
                'description' => 'Flutterwave payment method',
            ]);
        }
    }
}
