<?php

namespace Tests\Unit;

use DaaluPay\Mail\NewUser;
use Tests\TestCase;

class WelcomeEmailTest extends TestCase
{
    public function test_email_content()
    {
        // Test User
        $user = (object) [
            'id' => 1,
            'first_name' => 'Prince',
            'last_name' => 'Shammah',
            'email' => 'prince.shammah@wakexbiz.com',
            'password' => 'password123',
        ];
        // Arrange: Create a Mailable instance
        $mailable = new NewUser($user);

        // Act: Render the email
        $rendered = $mailable->render();
        dd($rendered);

        // Assert: Check if the email contains the expected content
        $this->assertStringContainsString('Welcome, Prince Shammah', $rendered);
        $this->assertStringContainsString('Thank you for signing up!', $rendered);
    }
}
