<?php

namespace DaaluPay\Http\Controllers\User;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use DaaluPay\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends BaseController
{
    /**
     * Display the authenticated user's preferences.
     */
    public function show()
    {
        $user = Auth::user();
        // Attempt to fetch the user's preferences. If not found, create a default record.
        $preferences = UserPreference::firstOrCreate(
            ['user_id' => $user->id],
            [
                'notify_email'            => true,
                'notify_sms'              => false,
                'notify_push'             => true,
                'theme'                   => 'light',
                'daily_transaction_limit' => 0.00,
                'transaction_total_today' => 0.00,
                'kyc_status'              => 'not_started',
                'two_fa_enabled'          => false,
            ]
        );

        return response()->json([
            'success' => true,
            'data'    => $preferences,
        ]);
    }

    /**
     * Update the authenticated user's preferences.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming data.
        $validatedData = $request->validate([
            'notify_email'            => 'sometimes|boolean',
            'notify_sms'              => 'sometimes|boolean',
            'notify_push'             => 'sometimes|boolean',
            'theme'                   => 'sometimes|in:light,dark',
            'daily_transaction_limit' => 'sometimes|numeric|min:0',
            'transaction_total_today' => 'sometimes|numeric|min:0',
            'last_transaction_date'   => 'sometimes|date',
            'kyc_status'              => 'sometimes|in:not_started,pending,verified,rejected',
            'two_fa_enabled'          => 'sometimes|boolean',
        ]);

        // Retrieve or create the user's preferences.
        $preferences = UserPreference::firstOrNew(['user_id' => $user->id]);
        $preferences->fill($validatedData);
        $preferences->save();

        return response()->json([
            'success' => true,
            'data'    => $preferences,
        ]);
    }
}
