@extends('emails.layout')

@section('title', 'Transfer Denied')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your submitted transfer (ID: {{ $transfer->id }}) has been denied.</p>
    <p><strong>Reason:</strong> {{ $reason }}</p>
    <p>Please review your submission and try again if necessary.</p>
@endsection
