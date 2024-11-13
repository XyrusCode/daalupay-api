<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
</head>
<body>
    <h1>Dear {{ $name }},</h1>
    <p>We are pleased to inform you that we have successfully processed your payment for **{{ $paymentPurpose }}**.</p>

    <p>**Details of Transaction:**</p>
    <ul>
        @foreach ($transactionDetails as $key => $value)
            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>

    <p>For further information, please don't hesitate to contact us at {{ $contactDetails }}.</p>
    <p>Thank you for your prompt payment.</p>
    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
