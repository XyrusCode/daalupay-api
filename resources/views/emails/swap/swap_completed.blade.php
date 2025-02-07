@extends('emails.layout')

@section('title', 'Swap Completed')

@section('content')
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your currency swap has been successfully completed.</p>
    <table style="width:100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;">From Currency:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $swap->from_currency }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;">To Currency:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $swap->to_currency }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;">Amount:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $swap->amount }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;">Rate:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $swap->rate }}</td>
        </tr>
    </table>
    <p>Thank you for using DaaluPay.</p>
@endsection
