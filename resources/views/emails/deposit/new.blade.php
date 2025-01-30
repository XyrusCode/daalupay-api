@extends('emails.layout')

@section('title', 'New Deposit')

@section('content')
    <h1>New Deposit</h1>
    <p>Hello {{ $user->first_name }},</p>
    <p>Your deposit for {{ $deposit->amount }}  has been successful.</p>
    <p>If you didn't request this, please contact us immediately.</p>
    <p>
        Our dedicated support team is here to help. Feel free to reach out to us at {{ config('mail.reply_to.address') }}.
    </p>
    <p>Thank you!</p>
@endsection
