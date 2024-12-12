<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 test users if not already created
        for ($i = 2; $i <= 6; $i++) {
            if (User::where('email', 'test' . $i . '@example.com')->first() === null) {
                User::create([
                    'first_name' => 'Test',
                    'last_name' => 'User ' . $i,
                    'email' => 'test' . $i . '@example.com',
                    'password' => Hash::make('password'),
                    'gender' => Faker::create()->randomElement(['male', 'female']),
                    'phone' => Faker::create()->phoneNumber,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
