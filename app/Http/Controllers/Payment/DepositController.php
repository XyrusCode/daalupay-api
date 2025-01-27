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
              // create a transaction
            $transaction = Transaction::create([
                'user_id' => request()->user()->id,
                'wallet_id' => $request->wallet_id,
                'amount' => $request->amount,
                'type' => 'deposit',
                'status' => 'pending',
                'payment_method' => 'deposit',
                
                'description' => 'Deposit for ' . $request->amount . ' ' . $request->currency,
            ]);

            $deposit = Deposit::create([
                'user_id' => request()->user()->id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'channel' => $request->channel,
                'wallet_id' => $request->wallet_id,
                'transaction_id' => $transaction->id,
            ]);

            // increment the wallet balance
            $wallet = Wallet::find($deposit->wallet_id);
            $wallet->balance += $deposit->amount;
            $wallet->save();



            return $this->getResponse('success', $deposit, 200);
        }, true);
    }
}
