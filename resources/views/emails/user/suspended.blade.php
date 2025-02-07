@extends('emails.layout')

@section('title', 'Account Suspended')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your account has been suspended for the following reason:</p>
    <p style="color: red; font-weight: bold;">{{ $reason }}</p>
    <p>Please contact support if you believe this is a mistake.</p>
@endsection
