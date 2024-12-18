<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DaaluPay\Models\PaymentMethod;
use Ramsey\Uuid\Uuid;
class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // If payment method already exists, skip for all payment methods
        if (!PaymentMethod::where('name', 'PayPal')->first()) {
            PaymentMethod::create([
                'uuid' => Uuid::uuid4(),
                'name' => 'PayPal',
                'description' => 'PayPal payment method'
            ]);
        }

        if (!PaymentMethod::where('name', 'Alipay')->first()) {
            PaymentMethod::create([
                'uuid' => Uuid::uuid4(),
                'name' => 'Alipay',
                'description' => 'Alipay payment method'
            ]);
        }

        if (!PaymentMethod::where('name', 'Bank Transfer')->first()) {
            PaymentMethod::create([
                'uuid' => Uuid::uuid4(),
                'name' => 'Bank Transfer',
                'description' => 'Direct Bank Transfer'
            ]);
        }

        if (!PaymentMethod::where('name', 'Paystack')->first()) {
            PaymentMethod::create([
                'uuid' => Uuid::uuid4(),
                'name' => 'Paystack',
                'description' => 'Paystack payment method'
            ]);
        }

        if (!PaymentMethod::where('name', 'Flutterwave')->first()) {
            PaymentMethod::create([
                'uuid' => Uuid::uuid4(),
                'name' => 'Flutterwave',
                'description' => 'Flutterwave payment method'
            ]);
        }
    }
}
