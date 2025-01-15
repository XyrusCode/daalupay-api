<?php

namespace DaaluPay\Http\Controllers\Admin;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use DaaluPay\Models\Admin;
use DaaluPay\Models\Currency;
use DaaluPay\Models\PaymentMethod;
use DaaluPay\Models\ExchangeRate;
use DaaluPay\Models\User;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\Swap;
use DaaluPay\Models\TransferFee;
use Illuminate\Support\Facades\DB;
use DaaluPay\Models\Wallet;

class SuperAdminController extends BaseController
{

    public function stats()
    {
        return $this->process(function () {


            $users = User::get();
            $swaps = Swap::get();

            $ngnCode = Currency::where('code', 'NGN')->first();

            // All naira wallet
            $nairaWallet = Wallet::where('currency_id', $ngnCode->id)->first();
            // total naira balance from all wallets
            $nairaBalance = $nairaWallet->balance;

            $userStats = [
                'total' => User::count(),
                'active' => User::where('status', 'active')->count(),
                'new' => User::where('status', 'unverified')->count(),
            ];

            $transactionStats = [
                'total' => Swap::count(),
                'pending' => Swap::where('status', 'pending')->count(),
                'approved' => Swap::where('status', 'approved')->count(),
                'rejected' => Swap::where('status', 'rejected')->count(),
            ];
            $stats = [
                'userStats' => $userStats,
                'transactionStats' => $transactionStats,
                'swaps' => $swaps,
                'nairaBalance' => $nairaBalance,
            ];
            return $this->getResponse(status: true, message: 'Super admin dashboard fetched successfully', data: $stats);
        }, true);
    }

    public function index()
    {
        $this->process(function () {
            $admin = Admin::find(auth('admin')->user()->id);
            return $this->getResponse(status: true, message: 'Super admin dashboard fetched successfully', data: $admin);
        }, true);
    }

    public function getAllAdmins(Request $request)
    {
        return $this->process(function () use ($request) {
            $admins = Admin::query();

            // Optionally filter admins by search or status
            if ($request->filled('search')) {
                $search = $request->input('search');
                $admins->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $admins->where('status', $request->input('status'));
            }

            $admins = $admins->paginate(15); // Paginate results

            return $this->getResponse(
                data: $admins,
                message: 'Admins retrieved successfully'
            );
        });
    }


    public function getAdmin(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $admin = Admin::find($id);

            if (!$admin) {
                return $this->getResponse(
                    status: 'error',
                    message: 'Admin not found',
                    status_code: 404
                );
            }

            return $this->getResponse(
                data: $admin,
                message: 'Admin retrieved successfully'
            );
        });
    }


    public function suspendAdmin(Request $request, Admin $admin)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $admin = Admin::find($id);
            if ($admin->status === 'suspended') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Admin is already suspended',
                    status_code: 400
                );
            }

            $admin->update(['status' => 'suspended']);

            return $this->getResponse(
                data: $admin,
                message: 'Admin suspended successfully'
            );
        });
    }

    public function reactivateAdmin(Request $request, Admin $admin)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $admin = Admin::find($id);
            if ($admin->status === 'active') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Admin is already active',
                    status_code: 400
                );
            }

            $admin->update(['status' => 'active']);

            return $this->getResponse(
                data: $admin,
                message: 'Admin reactivated successfully'
            );
        });
    }


    public function addAdmin(Request $request)
    {
        return $this->process(function () use ($request) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|string|min:8',
            ]);

            $admin = Admin::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'status' => 'active',
            ]);

            return $this->getResponse(
                data: $admin,
                message: 'Admin created successfully'
            );
        });
    }

    public function deleteAdmin(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $admin = Admin::find($id);
            $admin->delete();
            return $this->getResponse(status: true, message: 'Admin deleted successfully');
        });
    }


    public function getAllCurrencies(Request $request)
    {
        return $this->process(function () use ($request) {
            $currencies = Currency::query();

            if ($request->filled('status')) {
                $currencies->where('status', $request->input('status'));
            }

            return $this->getResponse(
                data: $currencies,
                message: 'Currencies retrieved successfully'
            );
        });
    }


    public function enableCurrency(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $currency = Currency::find($id);
            if ($currency->status === 'enabled') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Currency is already enabled',
                    status_code: 400
                );
            }

            $currency->update(['status' => 'enabled']);

            return $this->getResponse(
                data: $currency,
                message: 'Currency enabled successfully'
            );
        });
    }


    public function disableCurrency(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $currency = Currency::find($id);
            if ($currency->status === 'disabled') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Currency is already disabled',
                    status_code: 400
                );
            }

            $currency->update(['status' => 'disabled']);

            return $this->getResponse(
                data: $currency,
                message: 'Currency disabled successfully'
            );
        });
    }

    public function getAllPaymentMethods(Request $request)
    {
        return $this->process(function () use ($request) {
            $paymentMethods = PaymentMethod::query();
            $paymentMethods = $paymentMethods->get();
            return $this->getResponse(status: true, message: 'Payment methods fetched successfully', data: $paymentMethods);
        });
    }


    public function enablePaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $paymentMethod = PaymentMethod::find($id);
            if ($paymentMethod->status === 'enabled') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Payment method is already enabled',
                    status_code: 400
                );
            }

            $paymentMethod->update(['status' => 'enabled']);

            return $this->getResponse(
                data: $paymentMethod,
                message: 'Payment method enabled successfully'
            );
        });
    }


    public function disablePaymentMethod(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $paymentMethod = PaymentMethod::find($id);
            if ($paymentMethod->status === 'disabled') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Payment method is already disabled',
                    status_code: 400
                );
            }

            $paymentMethod->update(['status' => 'disabled']);

            return $this->getResponse(
                data: $paymentMethod,
                message: 'Payment method disabled successfully'
            );
        });
    }

    public function setExchangeRate(Request $request)
    {
        return $this->process(function () use ($request) {
            $exchangeRate = ExchangeRate::create($request->all());
            return $this->getResponse(true, 'Exchange rate created successfully', $exchangeRate);
        }, true);
    }

    public function getAllExchangeRates(Request $request)
    {
        return $this->process(function () use ($request) {
            $from = $request->query('from');
            $to = $request->query('to');
            // if no query params, return all exchange rates
            if (!$from && !$to) {
                $exchangeRate = DB::table('exchange_rate')->get();
                return $this->getResponse(status: true, message: 'Exchange rate fetched successfully', data: $exchangeRate);
            }

            $exchangeRate = DB::table('exchange_rate')->where('from_currency', $from)->where('to_currency', $to)->first();
            return $this->getResponse(status: true, message: 'Exchange rate fetched successfully', data: $exchangeRate);
        }, true);
    }

    public function getTransferFees(Request $request)
    {
        return $this->process(function () use ($request) {
            $transferFees = TransferFee::query();

            $transferFees = $transferFees->get();

            foreach ($transferFees as $transferFee) {
                $currency = Currency::where('id', $transferFee->currency_code)->first();
                $transferFee->currency = $currency->code;
            }

            return $this->getResponse(status: true, message: 'Transfer fee fetched successfully', data: $transferFees);
        }, true);
    }

    public function setTransferFee(Request $request)
    {
        return $this->process(function () use ($request) {
            // Validate the request
            $validated = $request->validate([
                'currency' => 'required|string|exists:currencies,code',
                'fee' => 'required|numeric|min:0'
            ]);

            $currency = Currency::where('code', $validated['currency'])->first();

            // Check if a transfer fee already exists for this currency
            $existingFee = TransferFee::where('currency_code', $currency->id)->first();

            if ($existingFee) {
                // Update existing fee
                $existingFee->update([
                    'fee' => $validated['fee'],
                    'updated_at' => now()
                ]);
                $transferFee = $existingFee;
            } else {
                // Create new fee
                $transferFee = TransferFee::create([
                    'currency_code' => $currency->id,
                    'fee' => $validated['fee'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $this->getResponse(
                status: true,
                message: 'Transfer fee ' . ($existingFee ? 'updated' : 'created') . ' successfully',
                data: $transferFee
            );
        }, true);
    }
}
