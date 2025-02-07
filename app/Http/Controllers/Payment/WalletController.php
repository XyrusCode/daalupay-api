<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Models\Admin;
use Illuminate\Http\Request;
use DaaluPay\Models\Wallet;
use DaaluPay\Models\Currency;
use DaaluPay\Models\Receipt;
use Illuminate\Support\Str;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\User;

class WalletController extends BaseController
{

    public function index(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $wallets = Wallet::where('user_id', $user->id)->get();

            // set currency name
            foreach ($wallets as $wallet) {
                $wallet->currency = Currency::find($wallet->currency_id)->code;
            }

            return $this->getResponse(
                'success',
                $wallets,
                200
            );
        }, true);
    }

    public function store(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $currency = $request->currency;
            $balance = 0;

            // Find currency or fail with meaningful message
            $currencyModel = Currency::where('code', $currency)->first();
            if (!$currencyModel) {
                return $this->getResponse('error', 'Invalid currency code: ' . $currency, 400);
            }

            $wallet = Wallet::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'currency_id' => $currencyModel->id,
                'balance' => $balance,
            ]);

            return $this->getResponse('success', $wallet, 200);
        }, true);
    }

    // receive receipt from alipay and store in database and assign it to an admin
    public function alipayVerify(Request $request)
    {
        return $this->process(function () use ($request) {
            $validated = $request->validate([
                'amount' => 'required|string',
                'receipt' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            ]);

            $receipt = $request->file('receipt')->store('receipts', 'public');

            $user = $request->user();
            $admin = null;
            // Select a random admin
            if (config('app.test_mode')) {
                $admin = Admin::where('id', 6)->first();
            } else {

                $admin = Admin::inRandomOrder()->first();
            }

            $receipt = Receipt::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'receipt' => $receipt,
                'admin_id' => $admin->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $admin = null;
            // Select a random admin
            if (config('app.test_mode')) {
                $admin = Admin::where('id', 6)->first();
            } else {

                $admin = Admin::inRandomOrder()->first();
            }

            return $this->getResponse('success', $request->all(), 200);
        }, true);
    }

    // receive receipt from alipay and store in database and assign it to an admin
    public function verifyAlipay(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $admin = null;
            // Select a random admin
            if (config('app.test_mode')) {
                $admin = Admin::where('id', 6)->first();
            } else {

                $admin = Admin::inRandomOrder()->first();
            }

            $validated = $request->validate([
                'amount' => 'required|string',
                'proof' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            ]);

            Receipt::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'receipt' => $validated['proof'],
                'admin_id' => $admin->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->getResponse('success', $request->all(), 200);
        }, true);
    }

    public function getWallet(Request $request, $uuid)
    {
        return $this->process(function () use ($request, $uuid) {
            $wallet = Wallet::where('uuid', $uuid)->first();

            $wallet->user = User::find($wallet->user_id);
            $wallet->currency = Currency::find($wallet->currency_id)->code;

            return $this->getResponse('success', $wallet, 200);
        }, true);
    }

    public function deleteWallet(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $wallet = Wallet::where('id', $id)->first();

            if (!$wallet) {
                return $this->getResponse('error', 'Wallet not found', 404);
            }

            //  get all user wallets
            $userWallets = Wallet::where('user_id', $wallet->user_id)->get();
            // find the wallet with currency 229
            $nairaWallet = $userWallets->where('currency_id', 229)->first();

            // convert all balance to naira
            $nairaWallet->balance += $wallet->balance;
            $nairaWallet->save();

            $wallet->delete();
            return $this->getResponse('success', $wallet, 200);
        }, true);
    }

    public function sendMoney(Request $request)
    {
        return $this->process(function () use ($request) {
            $validated = $request->validate([
                'amount' => 'required|string',
                'recipient_address' => 'required|string',
                'currency' => 'required|string',
            ]);

            $currency = Currency::where('code', $validated['currency'])->first();

            $user = $request->user();
            $wallet = Wallet::where('uuid', $validated['recipient_address'])->first();

            $userWallet = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->first();

            if (!$wallet) {
                return $this->getResponse('error', 'Wallet not found', 404);
            }

            $userWallet->balance -= $validated['amount'];
            $userWallet->save();

            $wallet->balance += $validated['amount'];
            $wallet->save();

            $transaction = Transaction::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'status' => 'pending',
                'reference_number' => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->getResponse('success', $request->all(), 200);
        }, true);
    }
}
