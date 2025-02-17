@extends('emails.layout')

@section('title', 'Admin Account Updated')

@section('content')
    <h1>Hello, {{ $admin->name }}</h1>
    <p>Your admin account has been updated</p>

    <p>Please contact super admin for further details.</p>
@endsection
