<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use DaaluPay\Services\FCMService;
use DaaluPay\Models\User;
use Illuminate\Support\Facades\Mail;
use DaaluPay\Mail\NewUser;

class TestController extends BaseController
{

        public function sendEmail()
    {
        $user = User::findOrFail(1);
        try {
            Mail::to($user->email)->send(new NewUser($user, 'secondArgument'));
            return response()->json(['message' => 'Email sent successfully for user ' . $user->first_name . ' ' . $user->last_name]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Email not sent!', 'error' => $e->getMessage()]);
        }
    }

    /**
     * Send a test FCM notification.
     *
     * Pass the device token as a query parameter (?token=YOUR_DEVICE_TOKEN)
     * or use a default token for testing.
     */
    public function testFcm(Request $request)
    {
        $token = 'd8n_x-Y1QdO2Z6u866Po7_:APA91bH0G9DgY00RbquJJqo1klf16GfadOqx2Vn80jcfIJrFEJ3FbxKxF8QSINHj1r98u0FGj5MrSIaGAvhZ05ahUI-BBsKwMCz-WBYXgYlirv197xeC6GY';
        // Use a device token provided in the query string or replace this with a valid test token
        $token = $request->query('token', $token);
        $title = 'Test Notification';
        $body  = 'This is a test notification from Firebase at '. now()->toDateTimeString();

        /** @var FCMService $fcm */
        $fcm = app(FCMService::class);

        try {
            // Attempt to send the notification
            $result = $fcm->sendNotification($token, $title, $body);

            // Return a success response with the result (if any)
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
                'result'  => $result,
            ]);
        } catch (MessagingException $e) {
            // Catches errors specific to messaging (e.g. invalid token, unregistered token, etc.)
            return response()->json([
                'success' => false,
                'error'   => 'Messaging error: ' . $e->getMessage(),
            ], 422);
        } catch (FirebaseException $e) {
            // Catches general Firebase exceptions
            return response()->json([
                'success' => false,
                'error'   => 'Firebase error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Catches any other exceptions
            return response()->json([
                'success' => false,
                'error'   => 'Unexpected error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
