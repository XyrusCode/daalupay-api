
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Processing Notification</title>
</head>
<body>
    <h1>Dear {{ $name }},</h1>
    <p>Thank you for submitting your payment request. We have received it and our team is currently processing it.</p>
    <p>Your transaction details are as follows:</p>
    <ul>
        @foreach ($transactionDetails as $key => $value)
            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>

    <p>We appreciate your patience while we process your payment.  If you have any questions, please do not hesitate to contact us at {{ $contactDetails }}.</p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
