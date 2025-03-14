<?php

namespace Database\Factories;

use DaaluPay\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\DaaluPay\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid,
            'balance' => fake()->randomFloat(2, 0, 10000),
            'currency' => 'NGN',
            'user_id' => User::factory(),
        ];
    }
}
