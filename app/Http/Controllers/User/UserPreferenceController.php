<?php

namespace DaaluPay\Http\Controllers\User;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends BaseController
{
    /**
     * Display the authenticated user's preferences.
     */
    public function show(Request $request)
    {
        return $this->process(
            function () {
                $user = Auth::user();
                // Attempt to fetch the user's preferences. If not found, create a default record.
                $preferences = UserPreference::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'notify_email' => 'true',
                        'notify_sms' => 'false',
                        'theme' => 'light',
                        'daily_transaction_limit' => '500000',
                        'transaction_total_today' => 0.00,
                        'two_fa_enabled' => 'false',
                    ]
                );

                return $this->getResponse('success', $preferences, 200);
            },
            true
        );
    }

    /**
     * Update the authenticated user's preferences.
     */
    public function update(Request $request)
    {
        return $this->process(
            function () use ($request) {
                $user = Auth::user();

                // Validate the incoming data.
                $validatedData = $request->validate([
                    'notify_email' => 'sometimes|string',
                    'notify_sms' => 'sometimes|string',
                    'theme' => 'sometimes|in:light,dark',
                    'daily_transaction_limit' => 'sometimes|numeric|min:0',
                    'transaction_total_today' => 'sometimes|numeric|min:0',
                    'last_transaction_date' => 'sometimes|date',
                    'two_fa_enabled' => 'sometimes|string',
                ]);

                // Retrieve or create the user's preferences.
                $preferences = UserPreference::firstOrNew(['user_id' => $user->id]);
                $preferences->fill($validatedData);
                $preferences->save();

                return $this->getResponse('success', $preferences, 200);
            },
            true
        );
    }
}
