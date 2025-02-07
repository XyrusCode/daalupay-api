@extends('emails.layout')

@section('title', 'Wallet Created')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>A new wallet has been added to your DaaluPay account.</p>
    <p><strong>Wallet Name:</strong> {{ $wallet->name }}</p>
    <p><strong>Wallet Address:</strong> {{ $wallet->address }}</p>
    <p>You can view and manage your wallets by logging into your account.</p>
@endsection
