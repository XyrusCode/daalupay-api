<div>
    <!-- Well begun is half done. - Aristotle -->
</div>
@extends('email.layout')

@section('title', 'Password Reset Request')

@section('content')
    <h1>Dear {{ $user->first_name }},</h1>
    <p>We have received your password reset request.</p>
    <p>Here is your reset link:</p>
    <p>
        <a href="{{ config('app.url') }}/password-reset/{{ $user->id }}/{{ $resetCode }}"
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
