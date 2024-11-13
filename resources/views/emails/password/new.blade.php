<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Password</title>
</head>
<body>
    <h1>New Password</h1>
    <p>Hello {{ $user->name }},</p>
    <p>Please your password has been reset successfully.</p>
    <p>If you didn't request this, please contact us immediately.</p>
    <p>
    Our dedicated support team is here to help, feel free to reach out to us at {{config('mail.reply_to.address')}}
    </p>
    <p>Thank you!</p>
</body>
</html>
