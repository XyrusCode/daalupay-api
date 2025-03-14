<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Mail\SwapCompleted;
use DaaluPay\Models\Admin;
use DaaluPay\Models\Currency;
use DaaluPay\Models\Swap;
use DaaluPay\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class SwapController extends BaseController
{
    public function index(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $swaps = Swap::where('user_id', $user->id)->get();

            return $this->getResponse('success', $swaps, 200);
        }, true);
    }

    public function show(Request $request, $swap_id)
    {
        return $this->process(function () use ($swap_id) {
            $swap = Swap::find($swap_id);

            if (! $swap) {
                return $this->getResponse('failure', null, 404, 'Swap not found');
            }

            return $this->getResponse('success', $swap, 200);
        }, true);
    }

    public function store(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();

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

            // Validate incoming request data
            $validated = $request->validate([
                'from_currency' => 'required|string',
                'to_currency' => 'required|string',
                'rate' => 'required|numeric',
                'from_amount' => 'required|numeric|min:0',
                'to_amount' => 'required|numeric|min:0',
                'fees' => 'nullable|numeric|min:0',
            ]);

            // Retrieve currency IDs, and check if they exist
            $fromCurrency = DB::table('currencies')
                ->where('code', $validated['from_currency'])
                ->first();

            $toCurrency = DB::table('currencies')
                ->where('code', $validated['to_currency'])
                ->first();

            if (! $fromCurrency) {
                return $this->getResponse(
                    status: false,
                    message: 'From currency not found',
                    data: null,
                    status_code: 404
                );
            }

            if (! $toCurrency) {
                return $this->getResponse(
                    status: false,
                    message: 'To currency not found',
                    data: null,
                    status_code: 404
                );
            }

            // Retrieve the wallets for the user for the specified currencies
            $from_wallet = $user->wallets()->where('currency_id', $fromCurrency->id)->first();
            $to_wallet = $user->wallets()->where('currency_id', $toCurrency->id)->first();

            if (! $from_wallet) {
                return $this->getResponse(
                    status: false,
                    message: 'From wallet not found',
                    data: null,
                    status_code: 404
                );
            }

            if (! $to_wallet) {
                return $this->getResponse(
                    status: false,
                    message: 'To wallet not found',
                    data: null,
                    status_code: 404
                );
            }

            // Check for sufficient balance in the from wallet
            if ($from_wallet->balance < $validated['from_amount']) {
                return $this->getResponse(
                    status: false,
                    message: 'Insufficient balance',
                    data: null,
                    status_code: 422
                );
            }

            $from_wallet->balance -= $validated['from_amount'];
            $from_wallet->save();

            $to_wallet->balance += $validated['to_amount'];
            $to_wallet->save();

            $admin = Admin::inRandomOrder()->first();

            // Create a new transaction record
            $transaction = Transaction::create([
                'uuid' => Uuid::uuid4(),
                'reference_number' => Uuid::uuid4(),
                'channel' => 'paystack',
                'amount' => $validated['to_amount'] + ($validated['fees'] ?? 0),
                'type' => 'swap',
                'status' => 'completed',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create the swap record
            $swap = Swap::create([
                'uuid' => Uuid::uuid4(),
                'user_id' => $user->id,
                'from_currency' => $validated['from_currency'],
                'to_currency' => $validated['to_currency'],
                'from_amount' => $validated['from_amount'],
                'to_amount' => $validated['to_amount'],
                'rate' => $validated['rate'],
                'status' => 'approved',
                'admin_id' => $admin->id,
                'transaction_id' => $transaction->id,
            ]);

            // update last transaction tiem and amoutn in user preferences
            $user->preferences->update([
                'transaction_total_today' => $user->preferences->transaction_total_today + $validated['from_amount'],
                'last_transaction_date' => now(),
            ]);

            Mail::to($user->email)->send(new SwapCompleted($user, $swap));

            // Send notifications to all active user device tokens
            $userDeviceTokens = $user->notificationTokens->where('status', 'active');
            foreach ($userDeviceTokens as $userDeviceToken) {
                $this->sendFCMNotification(
                    $request,
                    'Swap Approval',
                    'Your swap request has been created'
                );
            }

            return $this->getResponse(
                status: true,
                message: 'Swap created successfully',
                data: $swap,
                status_code: 200
            );
        }, true);
    }
}
