<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use DaaluPay\Models\ExchangeRate;
use DaaluPay\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
class ExchangeRateController extends BaseController
{
    public function index(Request $request)
    {
        $this->process(function () use ($request) {
            $from = $request->query('from');
            $to = $request->query('to');
           // if no query params, return all exchange rates
           if(!$from && !$to){
            $exchangeRate = DB::table('exchange_rate')->get();
            return $this->getResponse(status: true, message: 'Exchange rate fetched successfully', data: $exchangeRate);
           }

            $exchangeRate = ExchangeRate::where('from_currency', $request->from_currency)->where('to_currency', $request->to_currency)->first();
            return $this->getResponse(status: true, message: 'Exchange rate fetched successfully', data: $exchangeRate);


            // dummy response
            return $this->getResponse(status: true, message: 'Exchange rate fetched successfully', data: [
                'from_currency' => $from,
                'to_currency' => $to,
                'rate' => 100,
            ]);
    }, true);
    }


    public function store(Request $request)
    {
        $this->process(function () use ($request) {
            $exchangeRate = ExchangeRate::create($request->all());
            return $this->getResponse(true, 'Exchange rate created successfully', $exchangeRate);
        }, true);
    }

    public function update(Request $request, $uuid)
    {
        $this->process(function () use ($request, $uuid) {
            $exchangeRate = ExchangeRate::find($uuid);
            $exchangeRate->update($request->all());
            return $this->getResponse(true, 'Exchange rate updated successfully', $exchangeRate);
        }, true);
    }

    public function destroy($uuid)
    {
        $this->process(function () use ($uuid) {
            $exchangeRate = ExchangeRate::find($uuid);
            $exchangeRate->delete();
            return $this->getResponse(true, 'Exchange rate deleted successfully');
        }, true);
    }


}
