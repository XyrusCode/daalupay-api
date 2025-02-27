@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-danger" role="alert">
        Your withdrawal request has been denied.
    </div>
    <p>Reason: {{ $reason }}</p>
    <p>If you have any questions or believe this was a mistake, please contact our support team.</p>

</div>
@endsection
