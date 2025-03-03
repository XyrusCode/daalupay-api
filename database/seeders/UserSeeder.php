<?php

namespace Database\Seeders;

use DaaluPay\Models\Address;
use DaaluPay\Models\Admin;
use DaaluPay\Models\KYC;
use DaaluPay\Models\User;
use DaaluPay\Models\UserBankAccount;
use Daalupay\Models\UserPreference;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kycStatus = ['pending', 'approved', 'rejected'];

        $liveUsers = ['onebigjapan@gmail.com', 'Ehinmisan.olawale@gmail.com', 'uwahsheedo@gmail.com'];

        $admin = Admin::where('email', 'admin@daalupay.com')->first();

        // create known user for test operations
        User::create([
            'id' => 1,
            'uuid' => Uuid::uuid4(),
            'first_name' => 'Prince',
            'last_name' => 'Shammah',
            'email' => 'prince.shammah@walexbizhost.com',
            'password' => Hash::make('password'),
            'gender' => 'male',
            'phone' => '08123456789',
            'status' => 'active',
            'kyc_status' => 'approved',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        KYC::create([
            'user_id' => 1,
            'admin_id' => $admin->id,
            'status' => 'approved',
            'type' => 'individual',
            'document_type' => 'passport',
            'document_number' => Faker::create()->randomNumber(8, true),
            'document_image' => Faker::create()->imageUrl(),
            'passport_photo' => Faker::create()->imageUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Address::create([
            'user_id' => 1,
            'town' => Faker::create()->city,
            'state' => Faker::create()->state,
            'country' => Faker::create()->country,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        UserBankAccount::create([
            'user_id' => 1,
            'account_number' => Faker::create()->bankAccountNumber,
            'account_name' => 'Prince Shammah',
            'bank_name' => 'Walexbizhost',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create users for each email in $liveUsers
        foreach ($liveUsers as $index => $email) {
            $userId = $index + 2; // Start from 2 since 1 is already used
            $firstName = Faker::create()->firstName;
            $lastName = Faker::create()->lastName;

            User::create([
                'id' => $userId,
                'uuid' => Uuid::uuid4(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'gender' => Faker::create()->randomElement(['male', 'female']),
                'status' => 'unverified',
                'kyc_status' => $kycStatus[rand(0, 2)],
                'phone' => Faker::create()->phoneNumber,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Address::create([
                'user_id' => $userId,
                'town' => Faker::create()->city,
                'state' => Faker::create()->state,
                'country' => Faker::create()->country,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            KYC::create([
                'user_id' => $userId,
                'admin_id' => $admin->id,
                'status' => 'pending',
                'type' => 'individual',
                'document_type' => 'passport',
                'document_number' => Faker::create()->randomNumber(8, true),
                'document_image' => Faker::create()->imageUrl(),
                'passport_photo' => Faker::create()->imageUrl(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            UserBankAccount::create([
                'user_id' => $userId,
                'account_number' => Faker::create()->bankAccountNumber,
                'account_name' => $firstName . ' ' . $lastName,
                'bank_name' => Faker::create()->company,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

                    // UserPreference::create([
        // 'user_id' => $userId,
        //     'notify_email'            => 'true',
        //     'notify_sms'              => 'true',
        //     'theme'                   => 'light',
        //     'daily_transaction_limit' => '500000',
        //     'transaction_total_today' => '20000',
        //     'last_transaction_date' => now(),
        //     'kyc_status'              => 'pending',
        //     'two_fa_enabled'          => 'true',
        // ]);
        }

        // Create 5 test users if not already created
        for ($i = count($liveUsers) + 2; $i <= count($liveUsers) + 6; $i++) {
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
                    'status' => 'unverified',
                    'kyc_status' => $kycStatus[rand(0, 2)],
                    'phone' => Faker::create()->phoneNumber,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Address::create([
                    'user_id' => $i,
                    'town' => Faker::create()->city,
                    'state' => Faker::create()->state,
                    'country' => Faker::create()->country,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                KYC::create([
                    'user_id' => $i,
                    'admin_id' => $admin->id,
                    'status' => 'pending',
                    'type' => 'individual',
                    'document_type' => 'passport',
                    'document_number' => Faker::create()->randomNumber(8, true),
                    'document_image' => Faker::create()->imageUrl(),
                    'passport_photo' => Faker::create()->imageUrl(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                UserBankAccount::create([
                    'user_id' => $i,
                    'account_number' => Faker::create()->bankAccountNumber,
                    'account_name' => $firstName . ' ' . $lastName,
                    'bank_name' => Faker::create()->company,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
