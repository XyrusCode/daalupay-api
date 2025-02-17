@extends('emails.layout')

@section('title', 'Payment Approved')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your request to send money to Alipay (ID: {{ $receipt->recipient_alipay_id }}) has been approved.</p>
    find attached your receipt.
    <p>Amount: {{ $receipt->amount }}</p>
    <p>Recipient Alipay ID: {{ $receipt->recipient_alipay_id }}</p>
    <p>Recipient Name: {{ $receipt->recipient_name }}</p>

    <p>Here is the proof of payment</p>
    <img src="https://res.cloudinary.com/walexbizimage/upload/f_auto,q_auto/{{ $receipt->proof_of_ayment }}" alt="Receipt Image">
    <p>You can now view the updated status in your account.</p>
@endsection
