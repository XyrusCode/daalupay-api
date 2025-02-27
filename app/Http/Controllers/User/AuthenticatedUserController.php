<?php

namespace DaaluPay\Http\Controllers\User;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Models\Currency;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DaaluPay\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use DaaluPay\Models\Admin;
use DaaluPay\Models\KYC;
use DaaluPay\Models\Withdrawal;
use DaaluPay\Models\NotificationToken;
use DaaluPay\Models\UserBankAccount;
use DaaluPay\Services\FCMService;
use Illuminate\Support\Facades\Mail;
use DaaluPay\Mail\Withdrawal\WithdrawalRequest;

class AuthenticatedUserController extends BaseController
{
    /**
     * Handle an incoming authentication request.
     */
    public function show(Request $request)
    {

        return $this->process(function () use ($request) {

            $user = User::find($request->user()?->id);

            // load wallets and transactions if regular user
            $isUser = Auth::guard('user')->check();
            if (!$isUser) {
                $user->load('wallets', 'transactions');

                $user->wallets->each(function ($wallet) {
                    $wallet->currency = Currency::find($wallet->currency_id)->code;
                });
            }

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    public function showAdmin(Request $request)
    {
        return $this->process(function () use ($request) {
            $admin = auth('admin')->user() ?? auth('super_admin')->user();

            $message = 'success for user' . $request->user();

            return $this->getResponse(status: $message, data: $admin, status_code: 200);
        }, true);
    }

    public function stats(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = User::find($request->user()->id);

            $transactions = $user->transactions->take(5);
            $swaps = $user->swaps->take(5);

            $wallets = Wallet::where('user_id', $user->id)->get();

            // set currency name
            foreach ($wallets as $wallet) {
                $wallet->currency = Currency::find($wallet->currency_id)->code;
            }


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
        return $this->process(function () use ($request) {
            $user = User::find($request->user()->id);
            $user->update($request->all());
            return $this->getResponse('success', $user, 200);
        }, true);
    }

    public function updatePassword(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = Auth::user();

            $request->validate([
                'old_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8'], // TODO: Add more validation rules
            ]);

            if (!Hash::check($request->old_password, $user->password)) {
                return $this->getResponse('error', null, 400, 'Old password is incorrect');
            }

            // Update the user's password
            $user->password = Hash::make($request->new_password);

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    public function updatePin(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = Auth::user();

            $request->validate([
                'pin' => ['required', 'string', 'max:4'],
            ]);

            // Update the user's pin
            $user->pin = Hash::make($request->pin);
            $user->save();

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    public function verifyPin(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = Auth::user();

            $request->validate([
                'pin' => ['required', 'string', 'min:4'],
            ]);


            if (!Hash::check($request->pin, $user->pin)) {
                return $this->getResponse('error', null, 400, 'Pin is incorrect');
            }

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    public function createKyc(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();

            $validated = $request->validate([
                'documentType' => 'required|string|max:255',
                'documentFile' => 'required|string',
                'documentNumber' => 'required|string|max:255',
                'passportPhoto' => 'required|string',
            ]);

            $admin = Admin::where('role', 'processor')->inRandomOrder()->first();

            $kyc = KYC::create([
                'user_id' => $user->id,
                'document_type' => $validated['documentType'],
                'document_image' => $validated['documentFile'],
                'document_number' => $validated['documentNumber'],
                'passport_photo' => $validated['passportPhoto'],
                'admin_id' => $admin->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),


            ]);


            return $this->getResponse(status: 'success', message: 'KYC created successfully', status_code: 200);
        }, true);
    }

    public function storeNotificationToken(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $validated = $request->validate([
                'token' => 'required|string',
                'device_type' => 'required|string',
            ]);

            $token =  NotificationToken::create([
                'token' => $validated['token'],
                'device_type' => $validated['device_type'],
                'user_id' => $user->id,
                'status' => 'active',
            ]);

            return $this->getResponse('success', $token, 200);
        }, true);
    }

    public function deleteNotificationToken(Request $request, $id)
    {
        return $this->process(function () use ($request, $id) {
            $user = $request->user();
            $token = DB::table('device_token')->where('token', $id)->first();
            if (!$token) {
                return $this->getResponse('error', null, 404, 'Token not found');
            }
            DB::table('device_token')->where('token', $id)->update(['status' => 'inactive']);

            return $this->getResponse(status_code: 200, data: $token, status: 'success');
        }, true);
    }

    public function setBankAccount(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $validated = $request->validate([
                'account_number' => 'required|string',
                'bank_name' => 'required|string',
                'account_name' => 'required|string',
            ]);

            $bankAccount = UserBankAccount::updateOrCreate(
                ['account_number' => $validated['account_number']],
                [
                    'account_number' => $validated['account_number'],
                    'user_id' => $user->id,
                    'bank_name' => $validated['bank_name'],
                    'account_name' => $validated['account_name'],
                ]
            );

            return $this->getResponse('success', $bankAccount, 200);
        }, true);
    }

    public function getBankAccount(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $bankAccounts = UserBankAccount::where('user_id', $user->id)->get();

            if (!$bankAccounts) {
                return $this->getResponse('error', null, 404, 'Bank account not found');
            }

            return $this->getResponse('success', $bankAccounts, 200);
        }, true);
    }

    public function withdraw(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'bank_account_id' => 'required|numeric',
            ]);

            // get NGN wallet
            $ngnCurrency = Currency::where('code', 'NGN')->first();
            $wallet = Wallet::where('user_id', $user->id)->where('currency_id', $ngnCurrency->id)->first();

            $bankAccount = UserBankAccount::find($validated['bank_account_id']);

            // Assign random processor admin
            $admin = Admin::where('role', 'processor')->inRandomOrder()->first();
            $user = User::find($bankAccount->user_id);

            //if wallet balance is less than amount
            if ($wallet->balance < $validated['amount']) {
                return $this->getResponse('error', null, 400, 'Insufficient balance');
            }

            // create a transaction
            $transaction = Transaction::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'amount' => $validated['amount'],
                'type' => 'withdrawal',
                'status' => 'pending',
                'payment_method' => 'withdrawal',
                'reference_number' => Str::uuid(),
                'description' => 'Withdrawal for ' . $validated['amount'] . ' ' . $ngnCurrency->code,
            ]);

            $withdrawal = Withdrawal::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'currency_id' => $ngnCurrency->id,
                'wallet_id' => $wallet->id,
                'transaction_id' => $transaction->id,
                'bank_account_id' => $bankAccount->id,
                'bank_name' => $bankAccount->bank_name,
                'account_number' => $bankAccount->account_number,
                'proof_of_payment' => '',
                'reference' => '',
                'status' => 'pending',
                'admin_id' => $admin->id,
            ]);

            // decrement the wallet balance
            $wallet->balance -= $withdrawal->amount;
            $wallet->save();

            // Notify admin
            Mail::to($admin->email)->send(new WithdrawalRequest($admin, $user, $withdrawal));

            return $this->getResponse('success', $withdrawal, 200);
        }, true);
    }

    public function getWithdrawals(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $withdrawals = Withdrawal::where('user_id', $user->id)->get();

            return $this->getResponse('success', $withdrawals, 200);
        }, true);
    }
}
