@extends('emails.layout')

@section('title', 'Password Reset Request')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>You have requested to reset your password for your DaaluPay account.</p>
    <p>Please click on the link below to reset your password. This link is valid for {{ $expiration }} minutes:</p>
    <p>
        <a href="{{ config('app.frontend_url') }}/password-reset?userId={{ $user->uuid }}&token={{ $resetCode }}"
           style="display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Reset Password
        </a>
    </p>
    <p>
        Our dedicated support team is here to help. Feel free to reach out to us at {{ config('mail.reply_to.address') }}.
    </p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection

