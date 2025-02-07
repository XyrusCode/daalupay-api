@extends('emails.layout')

@section('title', 'KYC Approved')

@section('content')
    <h1>Congratulations, {{ $user->name }}!</h1>
    <p>Your KYC submission has been approved. You can now enjoy full access to our services.</p>
@endsection
