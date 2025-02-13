<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use DaaluPay\Models\Deposit;
use DaaluPay\Models\Wallet;
use DaaluPay\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use DaaluPay\Mail\NewDeposit;

class DepositController extends BaseController
{

    public function store(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();

            // check if transaction limit in preferences is unlimeted, if not check if exceeded
            if ($user->preferences->daily_transaction_limit != 'unlimited') {
                // if last transaction date more than 24 hours ago, reset transaction total today
                if ($user->preferences->last_transaction_date->diffInHours(now()) >= 24) {
                    $user->preferences->update([
                        'transaction_total_today' => 0,
                        'last_transaction_date'   => now(),
                    ]);
                }


                if ($user->preferences->transaction_total_today + $request['amount'] > $user->preferences->daily_transaction_limit) {
                    return $this->getResponse('error', 'Transaction limit exceeded', 400);
                }
            }

            // create a transaction
            $transaction = Transaction::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'wallet_id' => $request->wallet_id,
                'amount' => $request->amount,
                'type' => 'deposit',
                'status' => 'completed',
                'payment_method' => 'deposit',
                'reference_number' => Str::uuid(),
                'description' => 'Deposit for ' . $request->amount . ' ' . $request->currency,
            ]);

            $deposit = Deposit::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'channel' => $request->channel,
                'wallet_id' => $request->wallet_id,
                'transaction_id' => $transaction->id,
            ]);

            // increment the wallet balance
            $wallet = Wallet::where('id', $deposit->wallet_id)->first();
            $wallet->balance += $deposit->amount;
            $wallet->save();

            $user->preferences->update([
                'transaction_total_today' => $user->preferences->transaction_total_today + $request['amount'],
                'last_transaction_date'   => now(),
            ]);

            Mail::to($user->email)->send(new NewDeposit($user, $deposit));

            return $this->getResponse('success', $deposit, 200);
        }, true);
    }
}
