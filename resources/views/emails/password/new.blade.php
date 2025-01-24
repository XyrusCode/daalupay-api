@extends('emails.layout')

@section('title', 'New Password')

@section('content')
    <h1>New Password</h1>
    <p>Hello {{ $user->name }},</p>
    <p>Your password has been reset successfully.</p>
    <p>If you didn't request this, please contact us immediately.</p>
    <p>
        Our dedicated support team is here to help. Feel free to reach out to us at {{ config('mail.reply_to.address') }}.
    </p>
    <p>Thank you!</p>
@endsection
