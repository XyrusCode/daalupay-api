<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Models\Swap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use DaaluPay\Http\Traits\AdminTrait;
use DaaluPay\Models\User;
use DaaluPay\Models\Wallet;

class UserController extends BaseController
{

    public function get(Request $request)
    {
        $this->process(function () use ($request) {
            $user = $request->user();
            $userData = User::select(['id', 'name', 'email', 'created_at', 'updated_at'])
                ->with(['wallets:id,user_id,currency,balance'])
                ->where('id', $user->id)
                ->first();
            return $this->getResponse('success', $userData, 200);
        }, true);
    }

    public function stats(Request $request)
    {
        $this->process(function () use ($request) {
            $user = $request->user();
            $wallets = $user->wallets;
            // last 5 transactions
            $transactions = $user->transactions->take(5);
            $swaps = $user->swaps->take(5);

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
        $this->process(function () use ($request) {
            $user = $request->user();

            $user->save();
            $message = 'User updated successfully';
            return $this->getResponse('success', null, 200, $message);
        }, true);
    }


    public function updatePassword(Request $request)
    {
        $this->process(function () use ($request) {
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


    public function createWallet(Request $request)
    {
        $this->process(function () use ($request) {
            $user = $request->user();
            $currency = $request->currency;
            $balance = 0;

            Wallet::create([
                'user_id' => $user->id,
                'currency' => $currency,
                'balance' => $balance,
            ]);
        }, true);
    }


    public function getWallets(Request $request)
    {
        $this->process(function () use ($request) {
            $user = $request->user();
            $wallets = $user->wallets;
            return $this->getResponse('success', $wallets, 200);
        }, true);
    }


    public function getTransactions(Request $request)
    {
        $this->process(function () use ($request) {
            $user = $request->user();
            $transactions = $user->transactions;
            return $this->getResponse('success', $transactions, 200);
        }, true);
    }


    public function getSwaps(Request $request)
    {
        $this->process(function () use ($request) {
            $user = $request->user();
            $swaps = Swap::where('user_id', $user->id)->get();

            return $this->getResponse('success', $swaps, 200,
                'Swaps fetched successfully'
            );
        }, true);
    }
}

