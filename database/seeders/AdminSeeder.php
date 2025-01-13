<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Ramsey\Uuid\Uuid;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 admins
        for ($i = 1; $i <= 5; $i++) {
            $firstName = Faker::create()->firstName;
            $lastName = Faker::create()->lastName;
            Admin::create([
                'id' => $i,
                'uuid' => Uuid::uuid4(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $firstName . '.' . $lastName . '@daalupay.com',
                'password' => Hash::make('password'),
                'phone' => Faker::create()->phoneNumber,
                'status' => 'active',
                'role' => 'processor',
            ]);
        }

        // create a known admin
        Admin::create([
            'id' => 6,
            'uuid' => Uuid::uuid4(),
            'first_name' => 'Known',
            'last_name' => 'Admin',
            'email' => 'admin@daalupay.com',
            'password' => Hash::make('password'),
        ]);
    }
}
