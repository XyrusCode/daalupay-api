<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Mail\PaymentRequestCreated;
use DaaluPay\Models\Admin;
use DaaluPay\Models\Transfer;
use DaaluPay\Models\Currency;
use DaaluPay\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TransferController extends BaseController
{

    public function store(Request $request)
    {
        return $this->process(function () use ($request) {
            $validated = $request->validate([
                'amount' => 'required|string',
                'payment_details' => 'required|string',
                'recipient_name' => 'required|string',
                'recipient_email' => 'required|string',
                'description' => 'required|string',
                'currency' => 'required|string',
                'document_type' => 'required|string',
            ]);

            $currency = Currency::where('code', $validated['currency'])->first();

            // if code is 'NGN' throw error, not allowed
            if ($currency->code == 'NGN') {
                return $this->getResponse('error', 'Cannot send NGN', 400);
            }

            $user = $request->user();
            $admin = null;
            // Select a random admin of role processor
            $admin = Admin::where('role', 'processor')->inRandomOrder()->first();

            // check if transaction limit in preferences is unlimeted, if not check if exceeded
            if ($user->preferences->daily_transaction_limit != 'unlimited') {
                $lastTransactionDate = $user->preferences->last_transaction_date;

                // Bypass the check if the user has never made a transaction (last_transaction_date is null)
                if (! $lastTransactionDate) {
                    $user->preferences->update([
                        'last_transaction_date' => now(),
                    ]);
                }

                // if last transaction date more than 24 hours ago, reset transaction total today
                if ($lastTransactionDate && $lastTransactionDate->diffInHours(now()) >= 24) {
                    $user->preferences->update([
                        'transaction_total_today' => 0,
                        'last_transaction_date' => now(),
                    ]);
                }

                if ($user->preferences->transaction_total_today + $request['amount'] > $user->preferences->daily_transaction_limit) {
                    return $this->getResponse('error', 'Transaction limit exceeded', 400);
                }
            }

            $transaction = Transaction::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'channel' => 'alipay',
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'status' => 'pending',
                'reference_number' => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $payment = Transfer::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'status' => 'pending',
                'admin_id' => $admin->id,
                'payment_details' => $validated['payment_details'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_email' => $validated['recipient_email'],
                'description' => $validated['description'],
                'document_type' => $validated['document_type'],
                'transaction_id' => $transaction->id,
                'proof_of_payment' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // FInd wallet of user for the currency and deduct amount while it is pending
            $wallet = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->first();

            $wallet->balance -= $validated['amount'];
            $wallet->save();

            $user->preferences->update([
                'transaction_total_today' => $user->preferences->transaction_total_today + $transaction['amount'],
                'last_transaction_date' => now(),
            ]);

            
            // Notify the admin
            Mail::to($admin->email)->send(new PaymentRequestCreated($admin, $paymentRequest));

            return $this->getResponse('success', $payment, 200);
        }, true);
    }
    
    public function index(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $alipayTransfers = Transfer::where('user_id', $user->id)->get();

            return $this->getResponse('success', $alipayTransfers, 200);
        }, true);
    }

    public function show(Request $request, $id)
    {
        return $this->process(function () use ($id) {
            $alipayTransfer = Transfer::find($id);

            if (! $alipayTransfer) {
                return $this->getResponse('failure', null, 404, 'Alipay Transfer not found');
            }

            return $this->getResponse('success', $alipayTransfer, 200);
        }, true);
    }
}
