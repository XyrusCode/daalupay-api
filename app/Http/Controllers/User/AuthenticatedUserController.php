<?php

namespace DaaluPay\Http\Controllers\User;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Models\Currency;
use DaaluPay\Models\Swap;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DaaluPay\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class AuthenticatedUserController extends BaseController
{
    /**
     * Handle an incoming authentication request.
     */
    public function show(Request $request)
    {

        return $this->process(function () use ($request) {

                $user = User::find($request->user()?->id);

                $user->load('wallets', 'transactions');


                return $this->getResponse('success', $user, 200);

        }, true);
    }

    public function stats(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = User::find($request->user()->id);

            $transactions = $user->transactions->take(5);
            $swaps = $user->swaps->take(5);

            $wallets = Wallet::where('user_id', $user->id)->get();

            // set currency name
            foreach ($wallets as $wallet) {
                $wallet->currency = Currency::find($wallet->currency_id)->code;
            }


            $stats = [
                'wallets' => $wallets,
                'transactions' => $transactions,
                'swaps' => $swaps,
            ];
            return $this->getResponse('success', $stats, 200);
        }, true);
    }

    public function update(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = User::find($request->user()->id);
            $user->update($request->all());
            return $this->getResponse('success', $user, 200);
        }, true);
    }

    public function updatePassword(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = Auth::user();

            $request->validate([
                'old_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8'], // TODO: Add more validation rules
            ]);

            if (!Hash::check($request->old_password, $user->password)) {
                return $this->getResponse('error', null, 400, 'Old password is incorrect');
            }

            // Update the user's password
            $user->password = Hash::make($request->new_password);

            return $this->getResponse('success', $user, 200);
        }, true);
    }

}
