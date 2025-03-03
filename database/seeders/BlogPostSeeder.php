<?php

namespace Database\Seeders;

use DaaluPay\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogPost::create([
            'title' => 'An Intro to Alipay',
            'content' => 'Alipay is a digital payment platform that allows users to send and receive money, make payments, and manage their finances. It is a popular payment method in China and has become a global leader in mobile payments.',
            'featured_image' => '',
            'isActive' => true,
            'author_id' => 1,
        ]);

        BlogPost::create([
            'title' => 'The Benefits of Using Alipay',
            'content' => 'Alipay offers a wide range of benefits to its users. It is fast, secure, and convenient, allowing users to make payments quickly and easily. Alipay also offers a variety of features, such as bill payment, money transfer, and online shopping, making it a versatile payment platform.',
            'featured_image' => '',
            'isActive' => true,
            'author_id' => 1,
        ]);

        BlogPost::create([
            'title' => 'How to Use Alipay',
            'content' => 'Using Alipay is simple and easy. Users can download the Alipay app on their mobile device and create an account. They can then link their bank account or credit card to their Alipay account and start making payments. Alipay also offers a variety of features, such as QR code payments, money transfer, and online shopping, making it a versatile payment platform.',
            'featured_image' => '',
            'isActive' => true,
            'author_id' => 1,
        ]);
    }
}
