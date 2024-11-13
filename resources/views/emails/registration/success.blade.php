<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DaluuPay - Account Creation Successful!</title>
</head>
<body>
    <h1>Dear {{$user->first_name}},</h1>
    <p>Your account has been successfully created on DaluuPay and we're thrilled to have you on board.
</p>
    <p>Please reset your password after logging in.</p>
    <p>Your Login link is <a href="">{{$user->email}} to be fixed later</a></p>

    <p>Your email is: {{$employee->email}}</p>
    <p>Your password is: {{$password}}</p>
    <p>Please use this password to login to your account after activating your account.</p>

    <a style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;" href="{{ config('app.url') }}/activate-account/{{ $user->uuid }}/{{ $activationCode }}">Activate</a>
    <p>
    Our dedicated support team is here to help, feel free to reach out to us at {{config('mail.reply_to.address')}}
    </p>
    <p>
    Thank you for choosing DaluuPay,  We look forward to serving you and ensuring you have a seamless and enjoyable experience with us.
    </p>

    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
