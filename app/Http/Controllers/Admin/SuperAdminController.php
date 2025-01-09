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
use Illuminate\Support\Facades\DB;

class SuperAdminController extends BaseController
{

    public function stats()
    {
        return $this->process(function () {
            $admins = Admin::count();
            $users = User::count();
            $transactions = Transaction::count();
            $swaps = Swap::count();
            $stats = [
                'admins' => $admins,
                'users' => $users,
                'transactions' => $transactions,
                'swaps' => $swaps,
            ];
            return $this->getResponse(true, 'Super admin dashboard fetched successfully', $stats);
        }, true);
    }

    public function index()
    {
        $this->process(function () {
            $admin = Admin::find(auth('admin')->user()->id);
            return $this->getResponse(true, 'Super admin dashboard fetched successfully', $admin);
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


    public function getAdmin(Request $request, $id)
    {
        return $this->process(function () use ($id) {
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
        return $this->process(function () use ($admin) {
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
        return $this->process(function () use ($admin) {
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


    public function enableCurrency(Request $request, Currency $currency)
    {
        return $this->process(function () use ($currency) {
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


    public function disableCurrency(Request $request, Currency $currency)
    {
        return $this->process(function () use ($currency) {
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


    public function enablePaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        return $this->process(function () use ($paymentMethod) {
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


    public function disablePaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        return $this->process(function () use ($paymentMethod) {
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
}
