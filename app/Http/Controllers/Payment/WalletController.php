<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Mail\WalletCreated;
use DaaluPay\Models\Currency;
use DaaluPay\Models\PaymentMethod;
use DaaluPay\Models\User;
use DaaluPay\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            if (! $currencyModel) {
                return $this->getResponse('error', 'Invalid currency code: ' . $currency, 400);
            }

            $wallet = Wallet::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'currency_id' => $currencyModel->id,
                'balance' => $balance,
            ]);

            Mail::to($user->email)->send(new WalletCreated($user, $wallet));

            return $this->getResponse('success', $wallet, 200);
        }, true);
    }

    public function show(Request $request, $uuid)
    {
        return $this->process(function () use ($uuid) {
            $wallet = Wallet::where('uuid', $uuid)->first();

            $wallet->user = User::find($wallet->user_id);
            $wallet->currency = Currency::find($wallet->currency_id)->code;

            return $this->getResponse('success', $wallet, 200);
        }, true);
    }

    public function delete(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $wallet = Wallet::where('id', $id)->first();

            if (! $wallet) {
                return $this->getResponse('error', 'Wallet not found', 404);
            }

            // if wallet balance is above 0 return error
            if ($wallet->balance > 0) {
                return $this->getResponse('error', 'Wallet balance must be 0 to delete', 400);
            }

            $wallet->delete();

            return $this->getResponse('success', $wallet, 200);
        }, true);
    }

    public function getPaymentMethods(Request $request)
    {
        return $this->process(function () {
            $paymentMethods = PaymentMethod::query();
            // fiter where status is enabled
            $activeMethods = $paymentMethods->where('status', 'enabled')->get();

            return $this->getResponse(status: true, message: 'Payment methods fetched successfully', data: $activeMethods);
        });
    }
}
