<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use DaaluPay\Models\Payment;
use DaaluPay\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PaymentController extends BaseController
{

    public function createAndAssignPayment(Request $request)
    {
        return $this->process(function () use ($request) {
            // Validate incoming request
            $validated = $request->validate([
                'transaction_id' => 'required|uuid|exists:transactions,uuid',
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'method' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'channel' => 'required|string|max:255',
                'status' => 'required|string|in:pending,completed,failed',
            ]);

            // Retrieve the currently authenticated user
            $user = Auth::user();

            // Create the payment
            $payment = Payment::create([
                'name' => $validated['name'],
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'type' => $validated['type'],
                'channel' => $validated['channel'],
                'status' => $validated['status'],
                'user_id' => $user->id,
            ]);

            // Retrieve the transaction by UUID
            $transaction = Transaction::where('uuid', $validated['transaction_id'])->firstOrFail();

            // Assign the payment to the transaction
            $transaction->payment_id = $payment->id;
            $transaction->save();

            return response()->json([
                'message' => 'Payment created and assigned to transaction successfully',
                'payment' => $payment,
                'transaction' => $transaction,
            ]);
        }, true);
    }
}
