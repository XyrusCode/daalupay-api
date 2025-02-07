@extends('emails.layout')

@section('title', 'Account Verified')

@section('content')
    <h1>Congratulations, {{ $user->name }}!</h1>
    <p>Your DaaluPay account has been successfully verified. You can now enjoy all the benefits of our service.</p>
    <p>Thank you for trusting DaaluPay!</p>
@endsection
