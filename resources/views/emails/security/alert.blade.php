@extends('emails.layout')

@section('title', 'Security Alert')

@section('content')
    <h1>Hi {{ $user->name }},</h1>
    <p>We noticed some unusual activity on your DaaluPay account:</p>
    <p style="color: red; font-weight: bold;">{{ $alertMessage }}</p>
    @if($ipAddress)
        <p>IP Address: {{ $ipAddress }}</p>
    @endif
    <p>If this wasn’t you, please secure your account immediately by changing your password or contacting our support.</p>
@endsection
