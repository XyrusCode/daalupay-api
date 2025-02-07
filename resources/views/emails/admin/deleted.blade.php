@extends('emails.layout')

@section('title', 'Admin Account Deleted')

@section('content')
    <h1>Admin Account Deleted</h1>
    <p>This is a notification that the admin account for {{ $admin->name }} has been deleted.</p>
    <p>If this was not intended, please contact super admin immediately.</p>
@endsection
