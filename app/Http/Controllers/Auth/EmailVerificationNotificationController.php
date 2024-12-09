<?php

namespace DaaluPay\Http\Controllers\Auth;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends BaseController
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent']);
    }

    /**
     * Resend a new verificcation code
     */
    public function resendCode(Request $request){
         return $this->process(function () use ($request) {

            // Generate a new code for the user
            $code = $request->user()->createVerificationCode();

            // Send the new verification code to the user's email
            $request->user()->sendVerificationCode($code);

            return $this->getResponse(
                status: 'success',
                message: 'Verification code has been sent to your email',
                status_code: 200
            );
        });
    }


}
