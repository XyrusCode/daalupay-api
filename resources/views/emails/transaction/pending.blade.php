@extends('emails.layout')

@section('title', 'Payment Processing Notification')

@section('content')
    <h1>Dear {{ $user->name }},</h1>
    <p>Thank you for submitting your payment request. We have received it, and our team is currently processing it.</p>
    <p>Your transaction details are as follows:</p>
    <ul>
        @foreach ($transaction as $key => $value)
            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>
    <p>Please contact support for further assistance.</p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection
