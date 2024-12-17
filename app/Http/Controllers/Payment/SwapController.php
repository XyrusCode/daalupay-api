<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use DaaluPay\Models\Swap;

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

            $wallet = $user->wallets()->where('currency', 'NGN')->first();
            $wallet->balance -= $request->amount;
            $wallet->save();

            $swap = Swap::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'status' => 'pending',
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
