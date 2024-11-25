<?php

namespace DaaluPay\Http\Controllers;

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
        return $this->process(function() {
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
    public function show($transaction_id) {
        return $this->process(function() use ($transaction_id) {
            $transaction = Transaction::find($transaction_id);

            return $this->getResponse('success', $transaction, 200);
        }, true);
    }

    /**
     * Create a new transaction
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) {
        return $this->process(function() use ($request) {
            $user = Auth::user();

            $transaction = Transaction::create([
                'user_id' => $user->id,
            ]);
        }, true);
    }

    /**
     * Update a transaction
     * @param Request $request
     * @param int $transaction_id
     * @return JsonResponse
     */
    public function update(Request $request, $transaction_id) {
        return $this->process(function() use ($request, $transaction_id) {
            $transaction = Transaction::find($transaction_id);

            return $this->getResponse('success', $transaction, 200);
        }, true);
    }

    
}
