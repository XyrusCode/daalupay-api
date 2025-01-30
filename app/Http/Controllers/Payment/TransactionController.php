<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DaaluPay\Models\Transaction;

class TransactionController extends BaseController
{

    public function index()
    {
        return $this->process(function () {
            $user = Auth::user();

            $transactions = Transaction::where('user_id', $user->id)->get();

            return $this->getResponse('success', $transactions, 200);
        }, true);
    }


    public function show($transaction_id)
    {
        return $this->process(function () use ($transaction_id) {
            $transaction = Transaction::find($transaction_id);

            return $this->getResponse('success', $transaction, 200);
        }, true);
    }


    public function store(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = Auth::user();

            // Validate the request input
            $validated = $request->validate([
            'name' => 'required|string|max:255', // Payment name
            'amount' => 'required|numeric|min:0', // Payment amount
            'method' => 'required|string|max:255', // Payment method
            'channel' => 'required|string|max:255', // Payment channel
            'status' => 'required|string|in:pending,completed,failed', // Payment status
            'reference_number' => 'required|string|max:255|unique:transactions,reference_number',
            'type' => 'required|string|in:deposit,withdrawal,swap',
            'transaction_date' => 'required|date',
            'status' => 'required|string|in:pending,completed,canceled',
            ]);

                    // Get the available admin
            $admin = $this->getAvailableAdmin();

            // Create the transaction record and link it to the payment
            $transaction = Transaction::create([
            'reference_number' => $validated['reference_number'],
            'channel' => $validated['channel'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'status' => $validated['status'],
            'user_id' => $user->id,
            'admin_id' => $admin->id, // Assign admin
             ]);
 
                    // Increment the admin's workload
            $admin->increment('transactions_assigned');

            return $this->getResponse('success', $transaction, 201);
        }, true);
    }


    public function update(Request $request, $transaction_id)
    {
        return $this->process(function () use ($request, $transaction_id) {
            $transaction = Transaction::find($transaction_id);

            return $this->getResponse('success', $transaction, 200);
        }, true);
    }
}
