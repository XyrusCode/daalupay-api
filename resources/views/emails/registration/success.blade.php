@extends('emails.layout')

@section('title', 'DaluuPay - Account Creation Successful!')

@section('content')
    <h1>Dear {{ $user->first_name }},</h1>
    <p>
        Your account has been successfully created on DaluuPay, and we're thrilled to have you on board.
    </p>
    <p>Please reset your password after logging in.</p>
    <p>
        Your login link will be sent to your email <strong>{{ $user->email }}</strong>.
    </p>
    <p>Your credentials are as follows:</p>
    <ul>
        <li>Email: <strong>{{ $employee->email }}</strong></li>
        <li>Password: <strong>{{ $password }}</strong></li>
    </ul>
    <p>Please use the above credentials to log in after activating your account.</p>
    <p>
        <a style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;"
           href="{{ config('app.url') }}/activate-account/{{ $user->uuid }}/{{ $activationCode }}">
           Activate Account
        </a>
    </p>
    <p>
        Our dedicated support team is here to help. Feel free to reach out to us at {{ config('mail.reply_to.address') }}.
    </p>
    <p>
        Thank you for choosing DaluuPay! We look forward to serving you and ensuring a seamless and enjoyable experience with us.
    </p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection
