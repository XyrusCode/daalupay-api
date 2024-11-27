<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DaaluPay\Models\Transaction;

class TransactionController extends BaseController
{
    /**
     * Get all transactions for the authenticated user
     * @return JsonResponse
     */
    public function index()
    {
        return $this->process(function () {
            $user = Auth::user();

            $transactions = Transaction::where('user_id', $user->id)->get();

            return $this->getResponse('success', $transactions, 200);
        }, true);
    }

    /**
     * Get a transaction by ID
     * @param int $transaction_id
     * @return JsonResponse
     */
    public function show($transaction_id)
    {
        return $this->process(function () use ($transaction_id) {
            $transaction = Transaction::find($transaction_id);

            return $this->getResponse('success', $transaction, 200);
        }, true);
    }

/**
 * Create a new transaction along with its payment.
 *
 * @param Request $request
 * @return JsonResponse
 */
    public function store(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = Auth::user();

            // Validate the request input
            $validated = $request->validate([
            'name' => 'required|string|max:255', // Payment name
            'amount' => 'required|numeric|min:0', // Payment amount
            'method' => 'required|string|max:255', // Payment method
            'type' => 'required|string|max:255', // Payment type
            'channel' => 'required|string|max:255', // Payment channel
            'status' => 'required|string|in:pending,completed,failed', // Payment status
            'reference_number' => 'required|string|max:255|unique:transactions,reference_number',
            'send_currency' => 'required|string|max:3',
            'receive_currency' => 'required|string|max:3',
            'rate' => 'required|numeric|min:0',
            'fee' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'status' => 'required|string|in:pending,completed,canceled',
            ]);

                    // Get the available admin
            $admin = $this->getAvailableAdmin();

            // Create the payment record
            $payment = Payment::create([
            'name' => $validated['name'],
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'type' => $validated['type'],
            'channel' => $validated['channel'],
            'status' => $validated['status']
            ]);

            // Create the transaction record and link it to the payment
            $transaction = Transaction::create([
            'reference_number' => $validated['reference_number'],
            'channel' => $validated['channel'],
            'amount' => $validated['amount'],
            'send_currency' => $validated['send_currency'],
            'receive_currency' => $validated['receive_currency'],
            'rate' => $validated['rate'],
            'fee' => $validated['fee'],
            'transaction_date' => $validated['transaction_date'],
            'status' => $validated['status'],
            'user_id' => $user->id,
            'admin_id' => $admin->id, // Assign admin
            'payment_id' => $payment->id, // Link the transaction to the created payment
            ]);

                    // Increment the admin's workload
            $admin->increment('transactions_assigned');

            // Update the user's wallet balance
            if ($transaction->type === 'withdrawal') {
                $user->wallet->balance -= $transaction->amount;
                $user->wallet->save();
            }

            return response()->json([
            'message' => 'Transaction and payment created successfully',
            'payment' => $payment,
            'transaction' => $transaction,
            ], 201);
        }, true);
    }



    /**
     * Update a transaction
     * @param Request $request
     * @param int $transaction_id
     * @return JsonResponse
     */
    public function update(Request $request, $transaction_id)
    {
        return $this->process(function () use ($request, $transaction_id) {
            $transaction = Transaction::find($transaction_id);

            return $this->getResponse('success', $transaction, 200);
        }, true);
    }
}
