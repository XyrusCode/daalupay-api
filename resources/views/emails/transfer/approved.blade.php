@extends('emails.layout')

@section('title', 'Transfer Approved')

@section('content')
<h1>Hello, {{ $user->name }}</h1>
<p>Your request to send money to {{ $transfer->recipient_name }} has been approved.</p>
find attached your transfer.
<p>Amount: {{ $transfer->amount }}</p>
<p>Recipient Payment Info: {{ $transfer->payment_details }}</p>
<p>Recipient Name: {{ $transfer->recipient_name }}</p>

<p>Here is the proof of payment</p>
<img src="https://res.cloudinary.com/walexbizimage/upload/f_auto,q_auto/{{ $transfer->proof_of_ayment }}" alt="Transfer Image">
<p>You can now view the updated status in your account.</p>
@endsection