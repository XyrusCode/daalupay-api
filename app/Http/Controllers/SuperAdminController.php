<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use DaaluPay\Models\Admin;
use DaaluPay\Models\Currency;
use DaaluPay\Models\PaymentMethod;

class SuperAdminController extends AdminController
{
    /**
     * Suspend an admin.
     */
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

    /**
     * Reactivate an admin.
     */
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

    /**
     * Add a new admin.
     */
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

    /**
     * Enable a currency for exchange.
     */
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

    /**
     * Disable a currency for exchange.
     */
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

    /**
     * Enable a payment method.
     */
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

    /**
     * Disable a payment method.
     */
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
}
