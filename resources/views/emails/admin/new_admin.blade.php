@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success" role="alert">
        A new admin has been successfully added.
    </div>
    <p>Welcome, {{ $admin->name }}! You have been granted admin privileges.</p>
    <p>Here are your credentials</p>
    <p>Email: {{ $admin->email }}</p>
    <p>Password: {{ $password }}</p>
    <p>If you have any questions or believe this was a mistake, please contact our support team.</p>
</div>
@endsection
