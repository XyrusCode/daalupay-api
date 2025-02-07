@extends('emails.layout')

@section('title', 'Password Changed')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>This is a confirmation that your password has been successfully changed.</p>
    <p>If you did not make this change, please contact our support immediately.</p>
@endsection
