<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use DaaluPay\Models\User;
use Illuminate\Support\Facades\Mail;
use DaaluPay\Mail\NewUser;

class TestsController extends BaseController
{

    public function sendEmail()
    {
        $user = User::findOrFail(1);
        try {
            Mail::to($user->email)->send(new NewUser($user));
            return response()->json(['message' => 'Email sent successfully for user ' . $user->first_name . ' ' . $user->last_name]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Email not sent!', 'error' => $e->getMessage()]);
        }


    }
}
