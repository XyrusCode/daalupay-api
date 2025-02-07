@extends('emails.layout')

@section('title', 'Account Reactivated')

@section('content')
    <h1>Welcome back, {{ $user->name }}!</h1>
    <p>Your account has been reactivated. You can now continue using DaaluPay without interruption.</p>
@endsection
