@extends('emails.layout')

@section('title', 'Welcome to DaaluPay!')

@section('content')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="text-align: center;">
        <tr>
            <td>
                <h1 style="color: #333; font-size: 24px; font-weight: bold;">Welcome, {{ $user->name }}!</h1>
                <p style="color: #555; font-size: 16px;">
                    Thank you for joining <strong>DaaluPay</strong>. We're excited to have you with us!
                </p>
                <p style="color: #777; font-size: 16px;">Your One-Time Password (OTP) is:</p>
                <p style="background: #f3f3f3; display: inline-block; padding: 10px 20px; border-radius: 5px; font-size: 20px; font-weight: bold; color: #333;">
                    {{ $otp }}
                </p>
                <p style="color: #777; font-size: 14px; margin-top: 20px;">
                    Please use this OTP to complete your verification.
                </p>
            </td>
        </tr>
    </table>
@endsection
