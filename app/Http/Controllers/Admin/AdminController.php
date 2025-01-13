<?php

namespace DaaluPay\Http\Controllers\Admin;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use DaaluPay\Models\User;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\Suspension;
use DaaluPay\Models\Swap;
use DaaluPay\Models\KYC;
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

            // where admin_id is the admin id
            $transactions = Swap::where('admin_id', auth('admin')->user()->id)->get();
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
            $swap = Swap::find($id);

            $swap->update([
                'status' => 'rejected'
            ]);

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

    public function delete(Request $request, $user_id)
    {
        $this->process(function () use ($request, $user_id) {
            $this->is_admin($request);

            $user = User::find($user_id);

            if (!$user) {
                $message = 'User does not exist';
                return $this->getResponse('failure', null, 404, $message);
            }

            $user->delete();

            $message = 'User deleted successfully';
            return $this->getResponse('failure', null, 200, $message);
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
}
