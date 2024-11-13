<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
</head>
<body>
    <h1>Dear {{ $user->first_name }},</h1>
    <p>We have received your password reset request.</p>
    <p>Here is your reset link:</p>
    <a href="{{ config('app.url') }}/password-reset/{{ $user->id }}/{{ $resetCode }}">Reset</a>
    <p>
    Our dedicated support team is here to help, feel free to reach out to us at {{config('mail.reply_to.address')}}
    </p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
