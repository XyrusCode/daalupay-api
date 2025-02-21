@extends('emails.layout')

@section('title', 'Transaction Denied')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Unfortunately, your transaction (ID: {{ $swap->id }}) has been denied.</p>
    <p><strong>Reason:</strong> {{ $reason }}</p>
    <p>Please contact support for further assistance.</p>
@endsection
