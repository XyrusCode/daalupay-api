@extends('emails.layout')

@section('title', 'New Message Notification')

@section('content')
    <h1>Hello, {{ $recipient->name }}</h1>
    <p>You have received a new message from {{ $senderName }}:</p>
    <blockquote style="border-left: 4px solid #ccc; padding-left: 10px;">
        {{ $messageExcerpt }}
    </blockquote>
    <p>Log in to your account to read the full message and reply.</p>
@endsection
