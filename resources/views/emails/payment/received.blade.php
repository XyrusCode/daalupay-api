@extends('emails.layout')

@section('title', 'Payment Received')

@section('content')
    <h1>Hi {{ $transaction->recipient_name }},</h1>
    <p>You have received a payment on DaaluPay from {{ $user->first_name }} {{ $user->last_name }}</p>
    <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
    <p><strong>From:</strong> {{ $user->first_name ?? 'N/A' }}</p>
    <p><strong>Date:</strong> {{ $transaction->created_at->format('Y-m-d H:i') }}</p>
    <p>Here is the proof of payment</p>
    <img src="https://res.cloudinary.com/walexbizimage/upload/f_auto,q_auto/{{ $transaction->proof_of_payment }}" alt="Receipt Image">
    <p>Thank you for using DaaluPay.</p>
@endsection
