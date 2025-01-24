@extends('emails.layout')

@section('title', 'DaluuPay - Account Suspension!')

@section('content')
    <h1>Dear {{ $user->name }},</h1>
    <p>
        We regret to inform you that your account has been suspended.
    </p>
    <p><strong>Reason:</strong> {{ $suspension->reason }}</p>
    <p>
        Please contact the DaluuPay team to discuss the suspension and learn more about how to appeal this decision.
    </p>
    <p>
        Our dedicated support team is here to assist you. Feel free to reach out to us at {{ config('mail.reply_to.address') }}.
    </p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection
