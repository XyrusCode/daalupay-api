@extends('emails.layout')

@section('title', 'KYC Denied')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>We regret to inform you that your KYC submission has been denied.</p>
    <p><strong>Reason:</strong> {{ $reason }}</p>
    <p>Please review your submission and try again, or contact support for more information.</p>
@endsection
