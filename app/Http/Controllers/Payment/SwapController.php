<?php

namespace DaaluPay\Http\Controllers\Payment;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Models\Admin;
use Illuminate\Http\Request;
use DaaluPay\Models\Swap;
use DaaluPay\Notifications\SwapApprovalNotification;
use DaaluPay\Models\Currency;
use DaaluPay\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DaaluPay\Services\FCMService;
class SwapController extends BaseController
{
    protected $fcm;

    public function __construct(FCMService $fcm)
    {
        $this->fcm = $fcm;
    }

    public function index(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();
            $swaps = Swap::where('user_id', $user->id)->get();
            return $this->getResponse('success', $swaps, 200);
        }, true);
    }

    public function store(Request $request)
    {
        return $this->process(function () use ($request) {
            $user = $request->user();

            $request->validate([
                'from_currency' => 'required|string',
                'to_currency' => 'required|string',
                'rate' => 'required|string',
                'from_amount' => 'required|numeric|min:0',
                'to_amount' => 'required|numeric|min:0',
            ]);

            $from_currency_id = DB::table('currencies')->where('code', $request->from_currency)->first()->id;
            $to_currency_id = DB::table('currencies')->where('code', $request->to_currency)->first()->id;

            $from_wallet = $user->wallets()->where('currency_id', $from_currency_id)->first();
            $to_wallet = $user->wallets()->where('currency_id', $to_currency_id)->first();

            if (!$from_wallet) {
                return $this->getResponse(
                    status: false,
                    message: 'From wallet not found',
                    data: null,
                    status_code: 404
                );
            }

            if (!$to_wallet) {
                return $this->getResponse(
                    status: false,
                    message: 'To wallet not found',
                    data: null,
                    status_code: 404
                );
            }

            if ($from_wallet->balance < $request->amount) {
                return $this->getResponse(
                    status: false,
                    message: 'Insufficient balance',
                    data: null,
                    status_code: 422
                );
            }

            // remove from_wallet balance from from_wallet
            $from_wallet->balance -= $request->amount_to_swap;
            $from_wallet->save();

            // add to_wallet balance to to_wallet
            $to_wallet->balance += $request->amount_to_receive;
            $to_wallet->save();

            // random admin
            $admin = Admin::inRandomOrder()->first();
            // enabled tokens
            $userDeviceTokens = $user->notificationTokens->where('status', 'active');
            foreach ($userDeviceTokens as $userDeviceToken) {
                $this->fcm->sendToDevice($userDeviceToken->token, 'Swap Approval', 'Your swap request has been approved');
            }
            // ->notify(new SwapApprovalNotification($user, $request->amount, $request->from_currency, $request->to_currency, $request->from_amount, $request->to_amount, $request->rate));

            $transaction = Transaction::create([
                'uuid' => Uuid::uuid4(),
                'reference_number' => Uuid::uuid4(),
                'channel' => 'paystack',
                'amount' => $request->to_amount + $request->fees,
                'type' => 'swap',
                'status' => 'pending',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $transaction->save();

            $swap = Swap::create([
                'uuid' => Uuid::uuid4(),
                'user_id' => $user->id,
                'from_currency' => $request->from_currency,
                'to_currency' => $request->to_currency,
                'from_amount' => $request->from_amount,
                'to_amount' => $request->to_amount,
                'rate' => $request->rate,
                'status' => 'pending',
                'admin_id' => $admin->id,
                'transaction_id' => $transaction->id,
            ]);

            $swap->save();

            return $this->getResponse(
                status: true,
                message: 'Swap created successfully',
                data: $swap,
                status_code: 200
            );
        }, true);
    }


    public function show(Request $request, $swap_id)
    {
        return $this->process(function () use ($request, $swap_id) {
            $swap = Swap::find($swap_id);

            if (!$swap) {
                return $this->getResponse('failure', null, 404, 'Swap not found');
            }

            return $this->getResponse('success', $swap, 200);
        }, true);
    }
}
