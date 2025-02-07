@extends('emails.layout')

@section('title', 'Receipt Approved')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your submitted receipt (ID: {{ $receipt->id }}) has been approved.</p>
    <p>You can now view the updated status in your account.</p>
@endsection
