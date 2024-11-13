<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DaluuPay - Account Suspension!</title>
</head>
<body>
    <h1>Dear {{$user->name}},</h1>
    <p>Your account has been suspended.</p>
    <p>Reason: {{$suspension->reason}}</p>

    <p>Please contact the DaluuPay team to discuss the suspension and to find out how to appeal the decision.</p>
    <p>Our dedicated support team is here to help, feel free to reach out to us at {{config('mail.reply_to.address')}}
    </p>

    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
