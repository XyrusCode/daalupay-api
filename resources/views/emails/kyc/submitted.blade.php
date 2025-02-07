@extends('emails.layout')

@section('title', 'KYC Submitted')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>We have received your KYC submission. Our team will review your details and notify you of the outcome shortly.</p>
    <p>Thank you for helping us keep your account secure.</p>
@endsection
