<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\User;

class AdminController extends BaseController
{
    /**
     * Approve a transaction.
     */
    public function approveTransaction(Request $request, Transaction $transaction)
    {
        return $this->process(function () use ($request, $transaction) {
            if ($transaction->status !== 'pending') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Transaction is not pending approval',
                    status_code: 400
                );
            }

            $transaction->update([
                'status' => 'approved',
                'approved_by' => $request->user()->id,
            ]);

            return $this->getResponse(
                data: $transaction,
                message: 'Transaction approved successfully'
            );
        });
    }

    /**
     * Deny a transaction.
     */
    public function denyTransaction(Request $request, Transaction $transaction)
    {
        return $this->process(function () use ($request, $transaction) {
            if ($transaction->status !== 'pending') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Transaction is not pending approval',
                    status_code: 400
                );
            }

            $transaction->update([
                'status' => 'denied',
                'approved_by' => $request->user()->id,
            ]);

            return $this->getResponse(
                data: $transaction,
                message: 'Transaction denied successfully'
            );
        });
    }

    /**
     * Approve user verification.
     */
    public function approveUserVerification(Request $request, User $user)
    {
        return $this->process(function () use ($user, $request) {
            if ($user->verification_status === 'approved') {
                return $this->getResponse(
                    status: 'error',
                    message: 'User is already verified',
                    status_code: 400
                );
            }

            $user->update([
                'verification_status' => 'approved',
                'verified_by' => $request->user()->id,
            ]);

            return $this->getResponse(
                data: $user,
                message: 'User verification approved successfully'
            );
        });
    }

    /**
     * Suspend a user.
     */
    public function suspendUser(Request $request, User $user)
    {
        return $this->process(function () use ($user) {
            if ($user->status === 'suspended') {
                return $this->getResponse(
                    status: 'error',
                    message: 'User is already suspended',
                    status_code: 400
                );
            }

            $user->update(['status' => 'suspended']);

            return $this->getResponse(
                data: $user,
                message: 'User suspended successfully'
            );
        });
    }

    /**
     * Reactivate a user.
     */
    public function reactivateUser(Request $request, User $user)
    {
        return $this->process(function () use ($user) {
            if ($user->status === 'active') {
                return $this->getResponse(
                    status: 'error',
                    message: 'User is already active',
                    status_code: 400
                );
            }

            $user->update(['status' => 'active']);

            return $this->getResponse(
                data: $user,
                message: 'User reactivated successfully'
            );
        });
    }
}
