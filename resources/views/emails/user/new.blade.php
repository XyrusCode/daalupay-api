@extends('emails.layout')

@section('title', 'DaluuPay - New User!')

@section('content')
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Thank you for joining DaaluPay. We're excited to have you with us.</p>


@endsection
