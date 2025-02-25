@extends('emails.layout')

@section('title', 'Transaction Approved')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your withdrawal (ID: {{ $withdrawal->id }}) has been approved.</p>
    <p><strong>Amount:</strong> {{ $withdrawal->amount }}</p>
    <p><strong>Reference:</strong> {{ $withdrawal->reference }}</p>
    <p><strong>Transaction ID:</strong> {{ $withdrawal->transaction_id }}</p>
    <p><strong>Transaction Date:</strong> {{ $withdrawal->created_at }}</p>
    <p>Status: Approved</p>
    <p>Thank you for using DaaluPay.</p>
@endsection
