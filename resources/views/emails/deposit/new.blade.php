@extends('emails.layout')

@section('title', 'Deposit Initiated')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your deposit request has been initiated.</p>
    <p><strong>Amount:</strong> {{ $deposit->amount }}</p>
    <p><strong>Method:</strong> {{ $deposit->method }}</p>
    <p>Your deposit is being processed. We will notify you once it is completed.</p>
@endsection
