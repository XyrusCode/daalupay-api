@extends('emails.layout')

@section('title', 'Transaction Approved')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your transaction (ID: {{ $transaction->id }}) has been approved.</p>
    <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
    <p>Status: Approved</p>
    <p>Thank you for using DaaluPay.</p>
@endsection
