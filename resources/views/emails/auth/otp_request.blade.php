@extends('emails.layout')

@section('title', 'Your OTP Code')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>You requested a new One-Time Password (OTP). Your OTP is:</p>
    <p style="background: #f3f3f3; padding: 10px; font-size: 18px; font-weight: bold;">
        {{ $otp }}
    </p>
    <p>Please use this OTP to proceed with your request. If you did not request this, please contact support.</p>
@endsection
