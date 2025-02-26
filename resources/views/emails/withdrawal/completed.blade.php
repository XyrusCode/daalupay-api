@extends('emails.layout')

@section('title', 'Withdrawal Completed')

@section('content')
    <h1>Hello, {{ $user->first_name }}</h1>
    <p>Your withdrawal request has been completed.</p>
    <p>Amount: {{ $withdrawal->amount }}</p>
    <p>Bank Name: {{ $withdrawal->bank_name }}</p>
    <p>Account Number: {{ $withdrawal->account_number }}</p>
    <p>Account Name: {{ $withdrawal->account_name }}</p>
    <p>Thank you for using our service.</p>
@endsection
