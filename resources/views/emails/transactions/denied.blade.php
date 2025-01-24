@extends('emails.layout')

@section('title', 'Transaction Denied Notification')

@section('content')
    <h1>Dear {{ $name }},</h1>
    <p>
        We regret to inform you that your recent transaction request has been denied. Below are the details of the transaction:
    </p>
    <ul>
        @foreach ($transactionDetails as $key => $value)
            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>
    <p>
        If you believe this was a mistake or require further clarification, please don't hesitate to contact us at {{ $contactDetails }}.
    </p>
    <p>
        We value your trust and are here to assist you with any concerns or questions you might have.
    </p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection
