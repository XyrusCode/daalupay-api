<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use DaaluPay\Models\User;

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
