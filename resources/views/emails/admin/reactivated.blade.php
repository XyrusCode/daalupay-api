@extends('emails.layout')

@section('title', 'Admin Account Reactivated')

@section('content')
    <h1>Welcome back, {{ $admin->name }}</h1>
    <p>Your admin account has been reactivated.</p>
    <p>You can now resume your administrative duties.</p>
@endsection
