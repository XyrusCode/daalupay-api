<?php

namespace DaaluPay\Http\Controllers\Admin;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Mail\KycApproved;
use DaaluPay\Mail\KycDenied;
use DaaluPay\Mail\ReceiptApproved;
use DaaluPay\Mail\ReceiptDenied;
use DaaluPay\Mail\SwapCompleted;
use DaaluPay\Mail\TransactionDenied;
use DaaluPay\Mail\UserReactivated;
use DaaluPay\Mail\UserSuspended;
use DaaluPay\Models\Address;
use DaaluPay\Models\AlipayPayment;
use DaaluPay\Models\BlogPost;
use Illuminate\Http\Request;
use DaaluPay\Models\User;
use DaaluPay\Models\Transaction;
use DaaluPay\Models\Suspension;
use DaaluPay\Models\Swap;
use DaaluPay\Models\KYC;
use DaaluPay\Notifications\SwapStatusUpdated;
use Ramsey\Uuid\Uuid;
use DaaluPay\Models\Currency;
use DaaluPay\Models\Receipt;
use Illuminate\Support\Facades\URL;
use DaaluPay\Models\Wallet;
use DaaluPay\Mail\PaymentReceived;
use DaaluPay\Mail\UserDeleted;
use DaaluPay\Mail\UserUpdated;
use DaaluPay\Mail\Withdrawal\WithdrawalCompleted;
use DaaluPay\Mail\Withdrawal\WithdrawalRequest;
use DaaluPay\Mail\WithdrawalDenied;
use DaaluPay\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends BaseController
{

    public function stats()
    {
        return $this->process(function () {
            $admin_id = auth('admin')->user()->id;
            // last 5 transactions assigned to admin
            // $transactions = Transaction::where('admin_id', auth('admin')->user()->id)->orderBy('created_at', 'desc')->take(5)->get();
            // last 5 swaps assigned to admin
            // if admin os of role 'processor'
            $stats = [];
            if (auth('admin')->user()->role === 'processor') {

                $swaps = Swap::where('admin_id', $admin_id)->orderBy('created_at', 'desc')->take(5)->with('user')->get();
                $swaps = $swaps->filter(function ($swap) {
                    return $swap->user !== null;
                })->map(function ($swap) {
                    $user = $swap->user;
                    $swap->user->fullName = $user->firstName && $user->lastName
                        ? $user->firstName . ' ' . $user->lastName
                        : $user->email;


                    return $swap;
                })->values();
                // last 5 users assigned to admin
                // $users = User::where('admin_id', auth('admin')->user()->id)->orderBy('created_at', 'desc')->take(5)->get();
                // $kycs = KYC::where('admin_id', $admin_id)->orderBy('created_at', 'desc')->take(5)->get();
                $stats = [
                    // 'transactions' => $transactions,
                    'swaps' => $swaps,
                    // 'kycs' => $kycs,
                ];
            } else {
                // get blog stats
                $stats = [
                    'blogPosts' => BlogPost::count(),
                    'activeBlogPosts' => BlogPost::where('status', 'true')->count(),
                ];
            }


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

            if (!$user) {
                $message = 'User does not exist';
                return $this->getResponse('failure', null, 404, $message);
            }

            $user->update($request->all());

            $user->save();

            // Notify user via email
            Mail::to($user->email)->send(new UserUpdated($user));


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

            if ($user->status === 'suspended') {
                return $this->getResponse(
                    status: 'error',
                    message: 'User is already suspended',
                    status_code: 400
                );
            }

            $user->status = 'suspended';
            $user->save();

            $suspension = Suspension::create([
                'user_id' => $user->id,
            ]);

            Mail::to($user->email)->send(new UserSuspended($user, $request->reason));

            return $this->getResponse('success', $suspension, 200);
        }, true);
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

            // set transaction limit to unlimited if preferences exists
            if (!$user->preferences) {
                $user->preferences()->create([
                    'daily_transaction_limit' => 'unlimited',
                ]);
            }

            KYC::where('user_id', $user->id)->update([
                'status' => 'approved'
            ]);


            Mail::to($user->email)->send(new KycApproved($user));

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

            Mail::to($user->email)->send(new KycDenied($user, $request->reason));

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

            Mail::to($user->email)->send(new UserSuspended($user, $request->reason));

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

            Mail::to($user->email)->send(new UserReactivated($user));

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

            // mail user
            Mail::to($user->email)->send(new UserDeleted($user));

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

            // Notify user via email
            Mail::to($user->email)->send(new UserUpdated($user));

            $message = 'User updated successfully';
            return $this->getResponse('success', null, 200, $message);
        }, true);
    }

    public function getReceipts()
    {
        return $this->process(function () {
            $receipts = AlipayPayment::all();



            return $this->getResponse('success', $receipts, 200);
        }, true);
    }

    public function getReceipt($id)
    {
        return $this->process(function () use ($id) {
            $receipt = AlipayPayment::findOrFail($id);

            return $this->getResponse('success', $receipt, 200);
        }, true);
    }

    public function approveReceipt(Request $request, string $id)
    {
        return $this->process(function () use ($request, $id) {
            $request->validate([
                'proof_of_payment' => 'required'
            ]);

            $alipayPayment = AlipayPayment::find($id);

            //check if request has proof of payment
            if (!$request->has('proof_of_payment')) {
                return $this->getResponse(
                    status: 'error',
                    message: 'Proof of payment is required',
                    status_code: 400
                );
            }

            if ($alipayPayment->status === 'approved') {
                return $this->getResponse(
                    status: 'error',
                    message: 'Payment is already approved',
                    status_code: 400
                );
            }


            $alipayPayment->update([
                'proof_of_payment' => $request->proof_of_payment,
                'status' => 'completed'
            ]);
            $alipayPayment->save();

            $user = User::find($alipayPayment->user_id);

            // FInd User CNY Wallet
            $yuanCurrency = Currency::where('code', 'CNY')->first();
            $cnyWallet = Wallet::where('user_id', $user->id)->where('currency_id', $yuanCurrency->id)->first();

            $cnyWallet->balance -= $alipayPayment->amount;
            $cnyWallet->save();

            $transaction = Transaction::where('id', $alipayPayment->transaction_id)->first();
            $transaction->update([
                'status' => 'completed'
            ]);
            $transaction->save();

            Mail::to($user->email)->send(new ReceiptApproved($user, $alipayPayment));
            Mail::to($user->email)->send(new PaymentReceived($user, $alipayPayment));

            return $this->getResponse('success', $alipayPayment, 200);
        }, true);
    }

    public function denyReceipt(Request $request, string $id)
    {
        return $this->process(function () use ($request, $id) {
            $request->validate([
                'reason' => 'required'
            ]);

            $receipt = AlipayPayment::find($id);
            $receipt->update(['status' => 'rejected']);

            $user = User::find($receipt->user_id);

            Mail::to($user->email)->send(new ReceiptDenied($user, $receipt, $request->reason));
            return $this->getResponse('success', $receipt, 200);
        }, true);
    }

    public function getWithdrawals()
    {
        return $this->process(function () {
            $withdrawals = Withdrawal::all();

            return $this->getResponse('success', $withdrawals, 200);
        }, true);
    }

    public function getWithdrawal($id)
    {
        return $this->process(function () use ($id) {
            $withdrawal = Withdrawal::findOrFail($id);

            return $this->getResponse('success', $withdrawal, 200);
        }, true);
    }

    public function approveWithdrawal(Request $request)
    {
        return $this->process(function () use ($request) {
            // get id from route
            $id = $request->route('id');
            $withdrawal = Withdrawal::find($id);

            $request->validate([
                'proof_of_payment' => 'required'
            ]);


            //check if request has proof of payment
            if (!$request->has('proof_of_payment')) {
                return $this->getResponse(
                    status: 'error',
                    message: 'Proof of payment is required',
                    status_code: 400
                );
            }

            $user = User::find($withdrawal->user_id);

            $withdrawal->update([
                'proof_of_payment' => $request->proof_of_payment,
                'status' => 'approved',
                'reference' => $request->reference,
                'approved_at' => now(),
                'completed_at' => now(),
                'processed_at' => now()
            ]);
            $withdrawal->save();

            $transaction = Transaction::where('id', $withdrawal->transaction_id)->first();
            $transaction->update([
                'status' => 'completed',

            ]);
            $transaction->save();

            Mail::to($user->email)->send(new WithdrawalCompleted($user, $withdrawal));

            return $this->getResponse(
                data: $withdrawal,
                message: 'Withdrawal approved successfully'
            );
        }, true);
    }

    public function denyWithdrawal(Request $request)
    {
        return $this->process(function () use ($request) {
            // get id from route
            $id = $request->route('id');
            $reason = $request->reason;
            $withdrawal = Withdrawal::find($id);

            $withdrawal->update([
                'status' => 'rejected',
                'declined_at' => now(),
                'cancelled_at' => now(),
            ]);

            $user = User::find($withdrawal->user_id);

            Mail::to($user->email)->send(new WithdrawalDenied($user, $withdrawal, $reason));

            return $this->getResponse(
                data: $withdrawal,
                message: 'Withdrawal rejected successfully'
            );
        });
    }
}
