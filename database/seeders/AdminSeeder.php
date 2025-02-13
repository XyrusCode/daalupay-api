<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\Admin;
use DaaluPay\Models\SuperAdmin;
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


        // create a known admin
        Admin::create([
            'id' => 1,
            'uuid' => Uuid::uuid4(),
            'first_name' => 'Known',
            'last_name' => 'Admin',
            'email' => 'admin@daalupay.com',
            'role' => 'processor',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'id' => 2,
            'uuid' => Uuid::uuid4(),
            'first_name' => 'Blogger',
            'last_name' => 'Admin',
            'role' => 'blogger',
            'email' => 'blogger@daalupay.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'id' => 3,
            'uuid' => Uuid::uuid4(),
            'first_name' => 'Support',
            'last_name' => 'Admin',
            'email' => 'support@daalupay.com',
            'role' => 'support',
            'password' => Hash::make('password'),
        ]);

                // Create 5 admins
        for ($i = 4; $i <= 5; $i++) {
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
                'role' => $i % 2 === 0 ? 'processor' : 'support',
            ]);
        }

        SuperAdmin::create([
            'id' => 1,
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@daalupay.com',
            'password' => Hash::make('password'),
        ]);
    }
}
