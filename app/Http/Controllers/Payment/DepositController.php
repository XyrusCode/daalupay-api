<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use DaaluPay\Models\Deposit;
use DaaluPay\Models\Wallet;
use DaaluPay\Models\Transaction;

class DepositController extends BaseController
{

    public function store(Request $request)
    {
        return $this->process(function () use ($request) {
            $deposit = Deposit::create($request->all());

            // increment the wallet balance
            $wallet = Wallet::find($deposit->wallet_id);
            $wallet->balance += $deposit->amount;
            $wallet->save();

            // create a transaction
            $transaction = Transaction::create([
                'user_id' => $deposit->user_id,
                'wallet_id' => $deposit->wallet_id,
                'amount' => $deposit->amount,
                'type' => 'deposit',
                'status' => 'pending',
                'payment_method' => 'deposit',
                'payment_id' => $deposit->id,
                'description' => 'Deposit for ' . $deposit->amount . ' ' . $deposit->currency,
            ]);

            return $this->getResponse('success', $deposit, 200);
        }, true);
    }
}
