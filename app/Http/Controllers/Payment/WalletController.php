<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Models\Admin;
use Illuminate\Http\Request;
use DaaluPay\Models\Wallet;
use DaaluPay\Models\Currency;
use DaaluPay\Models\Receipt;
use Illuminate\Support\Str;

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
            $admin = Admin::inRandomOrder()->first();

            $receipt = Receipt::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'receipt' => $receipt,
                'admin_id' => $admin->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $admin = Admin::inRandomOrder()->first();

            return $this->getResponse('success', $request->all(), 200);
        }, true);
    }
}
