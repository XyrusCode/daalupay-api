<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Models\Admin;
use Illuminate\Http\Request;
use DaaluPay\Models\Swap;
use DaaluPay\Notifications\SwapApprovalNotification;

class SwapController extends BaseController
{

    public function index(Request $request)
    {
        return $this->process(function() use ($request) {
            $user = $request->user();
            $swaps = Swap::where('user_id', $user->id)->get();
            return $this->getResponse('success', $swaps, 200);
        }, true);
    }

    public function store(Request $request)
    {
        return $this->process(function() use ($request) {
            $user = $request->user();

            $request->validate([
                'from_currency' => 'required|string',
                'to_currency' => 'required|string',
                'amount' => 'required|numeric|min:0',
            ]);

            $from_wallet = $user->wallets()->where('currency', $request->from_currency)->first();
            $to_wallet = $user->wallets()->where('currency', $request->to_currency)->first();

            if (!$from_wallet) {
                return $this->getResponse('failure', null, 404, 'From wallet not found');
            }

            if (!$to_wallet) {
                return $this->getResponse('failure', null, 404, 'To wallet not found');
            }

            if ($from_wallet->balance < $request->amount) {
                return $this->getResponse('failure', null, 404, 'Insufficient balance');
            }

            $from_wallet->balance -= $request->amount;
            $to_wallet->balance += $request->amount;
            $from_wallet->save();
            $to_wallet->save();

           $admin = Admin::find(1)->notify(new SwapApprovalNotification($user, $request->amount, $request->from_currency, $request->to_currency, $request->from_amount, $request->to_amount, $request->rate));

            Swap::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'from_currency' => $request->from_currency,
                'to_currency' => $request->to_currency,
                'from_amount' => $request->from_amount,
                'to_amount' => $request->to_amount,
                'rate' => $request->rate,
                'status' => 'pending',
                'admin_id' => $admin->id,
            ]);

            return $this->getResponse('success', null, 200);
        }, true);
    }


    public function show(Request $request, $swap_id)
    {
        return $this->process(function() use ($request, $swap_id) {
            $swap = Swap::find($swap_id);

            if (!$swap) {
                return $this->getResponse('failure', null, 404, 'Swap not found');
            }

            return $this->getResponse('success', $swap, 200);
        }, true);
    }
}
