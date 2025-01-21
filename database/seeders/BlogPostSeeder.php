<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DaaluPay\Models\BlogPost;

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
    }
}
