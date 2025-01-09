<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use DaaluPay\Models\Wallet;
use DaaluPay\Models\Currency;
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
}
