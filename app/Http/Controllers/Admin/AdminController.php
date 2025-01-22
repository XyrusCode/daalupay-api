<?php

namespace DaaluPay\Http\Controllers\Admin;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Models\Address;
use Illuminate\Http\Request;
use DaaluPay\Models\User;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\Suspension;
use DaaluPay\Models\Swap;
use DaaluPay\Models\KYC;
use DaaluPay\Notifications\SwapStatusUpdated;
use Ramsey\Uuid\Uuid;
use DaaluPay\Models\Admin;
use DaaluPay\Models\Receipt;
use Illuminate\Support\Facades\Storage;

class AdminController extends BaseController
{

    public function stats()
    {
        return $this->process(function () {
            $admin_id = auth('admin')->user()->id;
            // last 5 transactions assigned to admin
            // $transactions = Transaction::where('admin_id', auth('admin')->user()->id)->orderBy('created_at', 'desc')->take(5)->get();
            // last 5 swaps assigned to admin
            $swaps = Swap::where('admin_id', $admin_id)->orderBy('created_at', 'desc')->take(5)->get();
            // last 5 users assigned to admin
            // $users = User::where('admin_id', auth('admin')->user()->id)->orderBy('created_at', 'desc')->take(5)->get();
            // $kycs = KYC::where('admin_id', $admin_id)->orderBy('created_at', 'desc')->take(5)->get();
            $stats = [
                // 'transactions' => $transactions,
                'swaps' => $swaps,
                // 'kycs' => $kycs,
            ];

            return $this->getResponse(status: true, message: 'Admin dashboard fetched where admin is ' . $admin_id, data: $stats);
        }, true);
    }

    public function getTransactions(Request $request)
    {
        return $this->process(function () use ($request) {
            $admin = auth('admin')->user() ?? auth('super_admin')->user();

            $transactions = Swap::where('admin_id', $admin->id)
                ->with('user')
                ->get();

            $transactions = $transactions->filter(function ($transaction) {
                return $transaction->user !== null;
            })->map(function ($transaction) {
                $user = $transaction->user;
                $transaction->user->fullName = $user->firstName && $user->lastName
                    ? $user->firstName . ' ' . $user->lastName
                    : $user->email;

                return $transaction;
            })->values();

            return $this->getResponse(status: true, message: 'Transactions fetched successfully', data: $transactions);
        }, true);
    }

    public function getTransaction($id)
    {
        return $this->process(function () use ($id) {
            $transaction = Swap::find($id);
            return $this->getResponse(status: true, message: 'Transaction fetched successfully', data: $transaction);
        }, true);
    }

    /**
     * Get all users
     * @return JsonResponse
     */
    public function getAllUsers()
    {
        return $this->process(function () {
            $users = User::all();

            foreach ($users as $user) {
                $user->address = Address::where('user_id', $user->id)->first();
                $user->kyc = KYC::where('user_id', $user->id)->first();
            }

            return $this->getResponse('success', $users, 200);
        }, true);
    }

    /**
     * Get a user by ID
     * @param int $user_id
     * @return JsonResponse
     */
    public function getUser($user_id)
    {
        return $this->process(function () use ($user_id) {
            $user = User::find($user_id);

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    /**
     * Update a user
     * @param Request $request
     * @param int $user_id
     * @return JsonResponse
     */
    public function updateUser(Request $request, $user_id)
    {
        return $this->process(function () use ($request, $user_id) {
            $user = User::find($user_id);

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    /**
     * Suspend a user and create a suspension record
     * @param Request $request
     * @return JsonResponse
     */
    public function createSuspension(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = User::find($request->user_id);

            $user->status = 'suspended';
            $user->save();

            $suspension = Suspension::create([
                'user_id' => $user->id,
            ]);

            return $this->getResponse('success', $suspension, 200);
        }, true);
    }

    public function approveTransaction(Request $request)
    {
        return $this->process(function () use ($request) {
            // get id from route
            $id = $request->route('id');
            $swap = Swap::find($id);


            $swap->update([
                'status' => 'approved'
            ]);

            $user = User::find($swap->user_id);

            $user->notify(new SwapStatusUpdated('approved'));

            return $this->getResponse(
                data: $swap,
                message: 'Swap approved successfully'
            );
        }, true);
    }

    public function denyTransaction(Request $request)
    {
        return $this->process(function () use ($request) {
            // get id from route
            $id = $request->route('id');
            $reason = $request->reason;
            $swap = Swap::find($id);

            $swap->update([
                'status' => 'rejected'
            ]);

            $user = User::find($swap->user_id);

            $user->notify(new SwapStatusUpdated('denied', $reason));

            return $this->getResponse(
                data: $swap,
                message: 'Swap rejected successfully'
            );
            return $this->getResponse(
                status: 'error',
                message: 'Transaction is not pending approval',
                status_code: 400
            );
        });
    }


    public function approveUserVerification(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $user = User::find($id);
            if ($user->kyc_status === 'approved') {
                return $this->getResponse(
                    status: 'error',
                    message: 'User is already verified',
                    status_code: 400
                );
            }

            $user->update([
                'kyc_status' => 'approved',
                'verified_by' => $request->user()->id,
            ]);

            KYC::where('user_id', $user->id)->update([
                'status' => 'approved'
            ]);

            return $this->getResponse(
                status: 'success',
                data: $user,
                message: 'User verification approved successfully'
            );
        });
    }

    public function denyUserVerification(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $user = User::find($id);
            $user->update(['kyc_status' => 'rejected']);
            KYC::where('user_id', $user->id)->update([
                'status' => 'rejected',
                'reason' => $request->reason,
            ]);

            return $this->getResponse(
                status: 'success',
                data: $user,
                message: 'User verification denied successfully'
            );
        });
    }


    public function suspendUser(Request $request)
    {
        return $this->process(function () use ($request) {
            $id = $request->route('id');
            $user = User::find($id);

            if ($user->status === 'suspended') {
                return $this->getResponse(
                    status: 'error',
                    message: 'User is already suspended',
                    status_code: 400
                );
            }

            $user->update(['status' => 'suspended']);

            // Check for existing suspension
            $existingSuspension = Suspension::where('user_id', $id)
                ->where('status', 'ongoing')
                ->first();

            if ($existingSuspension) {
                // Update existing suspension
                $existingSuspension->update([
                    'reason' => $request->reason,
                    'start_date' => now(),
                    'admin_id' => $request->user()->id,
                ]);
                $suspension = $existingSuspension;
            } else {
                // Create new suspension
                $suspension = Suspension::create([
                    'uuid' => Uuid::uuid4(),
                    'user_id' => $user->id,
                    'reason' => $request->reason,
                    'start_date' => now(),
                    'status' => 'ongoing',
                    'admin_id' => $request->user()->id,
                ]);
            }

            return $this->getResponse(
                data: $user,
                message: 'User suspended successfully'
            );
        });
    }


    public function reactivateUser(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = User::find($request->route('id'));

            if ($user->status === 'active') {
                return $this->getResponse(
                    status: 'error',
                    message: 'User is already active',
                    status_code: 400
                );
            }

            $user->update(['status' => 'active']);

            $suspension = Suspension::where('user_id', $user->id)->where('status', 'ongoing')->first();

            if ($suspension) {
                $suspension->update(['status' => 'ended']);
                $suspension->update(['reactivation_reason' => $request->reason]);
            }

            return $this->getResponse(
                data: $user,
                message: 'User reactivated successfully'
            );
        });
    }

    public function deleteUser(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = User::find($request->route('id'));

            if (!$user) {
                $message = 'User does not exist';
                return $this->getResponse(status: false, message: $message, data: null, status_code: 404);
            }

            $user->delete();

            $message = 'User deleted successfully';
            return $this->getResponse(status: true, message: $message, data: null, status_code: 200);
        }, true);
    }


    public function updateDetails(Request $request, $user_id)
    {
        $this->process(function () use ($request, $user_id) {
            $this->is_admin($request);

            $user = User::find($user_id);

            if (!$user) {
                $message = 'User does not exist';
                return $this->getResponse('failure', null, 404, $message);
            }

            $user->first_name = $request->first_name ?? $user->first_name;
            $user->last_name = $request->last_name ?? $user->last_name;
            $user->phone_number = $request->phone_number ?? $user->phone_number;
            $user->role = $request->role ?? $user->role;

            $user->save();

            $message = 'User updated successfully';
            return $this->getResponse('success', null, 200, $message);
        }, true);
    }

    public function getReceipts()
    {
        return $this->process(function () {
            $receipts = Receipt::all();

            //geet url for receipt
            foreach ($receipts as $receipt) {
                $receipt->receipt = Storage::url($receipt->receipt);
            }
            return $this->getResponse('success', $receipts, 200);
        }, true);
    }

    public function getReceipt($id)
    {
        return $this->process(function () use ($id) {
            $receipt = Receipt::find($id);
            return $this->getResponse('success', $receipt, 200);
        }, true);
    }

    public function approveReceipt($id)
    {
        return $this->process(function () use ($id) {
            $receipt = Receipt::find($id);
            $receipt->update(['status' => 'approved']);
            return $this->getResponse('success', $receipt, 200);
        }, true);
    }

    public function denyReceipt($id)
    {
        return $this->process(function () use ($id) {
            $receipt = Receipt::find($id);
            $receipt->update(['status' => 'rejected']);
            return $this->getResponse('success', $receipt, 200);
        }, true);
    }


}
