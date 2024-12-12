<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use DaaluPay\Http\Traits\AdminTrait;
use DaaluPay\Models\User;
use DaaluPay\Models\Wallet;
class UserController extends BaseController
{
    /**
     * Get the authenticated user's details
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        $this->process(function() use ($request) {
            // Load wallets and transactions
            $user = $request->user();
            $user->load('wallets', 'transactions');

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    /**
     * Update the authenticated user's details
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $this->process(function() use ($request) {
            $user = $request->user();

        $user->save();
        $message = 'User updated successfully';
            return $this->getResponse('success', null, 200, $message);
        }, true);
    }

    /**
     * Update the authenticated user's password
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $this->process(function() use ($request) {
        $request->validate([
            'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8'], // TODO: Add more validation rules
        ]);

        $user = Auth::user();

        // Check if the old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->getResponse('error', null, 400, 'Old password is incorrect');
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);

        return $this->getResponse('success', null, 200, 'Password updated successfully');

    }, true);
}



    /**
     * Create a wallet for a user in a currency
     */
    public function createWallet(Request $request)
    {
       $this->process(function() use ($request) {
        $user = $request    ->user();
        $currency = $request->currency;
        $balance = 0;

        Wallet::create([
            'user_id' => $user->id,
            'currency' => $currency,
            'balance' => $balance,
        ]);
       }, true);
    }

    /**
     * Get a user's wallets
     */
    public function getWallets(Request $request)
    {
        $this->process(function() use ($request) {
            $user = $request->user();
            $wallets = $user->wallets;
            return $this->getResponse('success', $wallets, 200);
        }, true);
    }

    /**
     * Get a user's transactions
     */
    public function getTransactions(Request $request)
    {
        $this->process(function() use ($request) {
            $user = $request->user();
            $transactions = $user->transactions;
            return $this->getResponse('success', $transactions, 200);
        }, true);
    }

    /**
     * Get a user's swaps
     */
    public function getSwaps(Request $request)
    {
        $this->process(function() use ($request) {
            $user = $request->user();
            $swaps = $user->swaps;
            return $this->getResponse('success', $swaps, 200);
        }, true);
    }
}
