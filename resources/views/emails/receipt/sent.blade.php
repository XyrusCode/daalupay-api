@extends('emails.layout')

@section('title', 'Payment Approved')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your request to send money to Alipay (ID: {{ $alipayPayment->recipient_alipay_id }}) has been approved.</p>
    find attached your receipt.
    <p>Amount: {{ $alipayPayment->amount }}</p>
    <p>Recipient Alipay ID: {{ $alipayPayment->recipient_alipay_id }}</p>
    <p>Recipient Name: {{ $alipayPayment->recipient_name }}</p>

    <p>Here is the proof of payment</p>
    <img src="https://res.cloudinary.com/walexbizimage/upload/f_auto,q_auto/{{ $alipayPayment->proof_of_ayment }}" alt="Receipt Image">
    <p>You can now view the updated status in your account.</p>
@endsection
