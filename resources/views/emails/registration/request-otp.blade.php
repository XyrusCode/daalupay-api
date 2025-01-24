@extends('emails.layout')

@section('title', 'DaluuPay - OTP Verification')

@section('content')
    <h1>Dear {{ $user->first_name }},</h1>
    <p>Your OTP verification code is: {{ $otp }}</p>
    <p>This code will expire in {{ $validityInMinutes }} minutes.</p>
    <p>If you did not request this code, please ignore this email.</p>
@endsection
