@extends('emails.layout')

@section('title', 'Withdrawal Request')

@section('content')
    <h1>Hello, {{ $admin->first_name }}</h1>
    <p>{{ $user->first_name }} has requested to withdraw money from their account.</p>
    <p>Amount: {{ $withdrawal->amount }}</p>
    <p>Bank Name: {{ $withdrawal->bank_name }}</p>
    <p>Account Number: {{ $withdrawal->account_number }}</p>
    <p>Account Name: {{ $withdrawal->account_name }}</p>
    <p>Please login to your dashboard to process this.</p>

@endsection
