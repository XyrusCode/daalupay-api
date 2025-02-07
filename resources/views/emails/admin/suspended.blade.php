@extends('emails.layout')

@section('title', 'Admin Account Suspended')

@section('content')
    <h1>Hello, {{ $admin->name }}</h1>
    <p>Your admin account has been suspended for the following reason:</p>
    <p style="color: red; font-weight: bold;">{{ $reason }}</p>
    <p>Please contact super admin for further details.</p>
@endsection
