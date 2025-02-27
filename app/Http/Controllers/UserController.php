<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Models\User;
// use DaaluPay\Http\Traits\AdminTrait;
use Illuminate\Http\Request;

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
}
