@extends('emails.layout')

@section('title', 'Payment Sent')

@section('content')
    <h1>Hello {{ $user->name }},</h1>
    <p>Your payment has been successfully sent from your DaaluPay account.</p>
    <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
    <p><strong>To:</strong> {{ $transaction->recipient_name ?? 'N/A' }}</p>
    <p><strong>Date:</strong> {{ $transaction->created_at->format('Y-m-d H:i') }}</p>
    <p>If you did not authorize this transaction, please contact support immediately.</p>
@endsection
