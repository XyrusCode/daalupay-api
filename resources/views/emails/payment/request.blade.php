@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success" role="alert">
        A new payment request has been created.
    </div>
    <p>Dear {{ $admin->first_name }},</p>
    <p>Your payment request of {{ $payment->amount }} has been successfully created. Please review the details below:</p>
    <ul>
        <li>Request ID: {{ $payment->id }}</li>
        <li>Amount: {{ $payment->amount }}</li>
        <li>Date: {{ $payment->created_at->format('d-m-Y') }}</li>
    </ul>
    <p>Please login to your dashboard and review.</p>

</div>
@endsection
