<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kycStatus = ['pending', 'approved', 'rejected'];

        // create known user for test  operations
        User::create([
            'id' => 1,
            'uuid' => Uuid::uuid4(),
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'gender' => 'male',
            'phone' => '08123456789',
            'status' => 'active',
            'kyc_status' => 'approved',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create 5 test users if not already created
        for ($i = 2; $i <= 6; $i++) {
            $firstName = Faker::create()->firstName;
            $lastName = Faker::create()->lastName;
            $email = $firstName . '.' . $lastName . '@example.com';
            if (User::where('email', $email)->first() === null) {
                User::create([
                    'id' => $i,
                    'uuid' => Uuid::uuid4(),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'gender' => Faker::create()->randomElement(['male', 'female']),
                    'status' => 'active',
                    'kyc_status' => $kycStatus[rand(0, 2)],
                    'phone' => Faker::create()->phoneNumber,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
