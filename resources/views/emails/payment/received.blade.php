@extends('emails.layout')

@section('title', 'Payment Received')

@section('content')
    <h1>Hi {{ $user->name }},</h1>
    <p>You have received a payment on your DaaluPay account!</p>
    <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
    <p><strong>From:</strong> {{ $transaction->sender_name ?? 'N/A' }}</p>
    <p><strong>Date:</strong> {{ $transaction->created_at->format('Y-m-d H:i') }}</p>
    <p>Thank you for using DaaluPay.</p>
@endsection
