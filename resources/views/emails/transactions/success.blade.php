@extends('emails.layout')

@section('title', 'Payment Confirmation')

@section('content')
    <h1>Dear {{ $name }},</h1>
    <p>
        We are pleased to inform you that we have successfully processed your payment for <strong>{{ $paymentPurpose }}</strong>.
    </p>
    <p><strong>Details of Transaction:</strong></p>
    <ul>
        @foreach ($transactionDetails as $key => $value)
            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>
    <p>
        For further information, please don't hesitate to contact us at {{ $contactDetails }}.
    </p>
    <p>Thank you for your prompt payment.</p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection
