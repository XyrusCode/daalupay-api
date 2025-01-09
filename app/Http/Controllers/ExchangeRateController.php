<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use DaaluPay\Models\ExchangeRate;
use DaaluPay\Http\Controllers\BaseController;
class ExchangeRateController extends BaseController
{
    public function index(Request $request)
    {
        $this->process(function () use ($request) {
            $exchangeRate = ExchangeRate::where('from_currency', $request->from_currency)->where('to_currency', $request->to_currency)->first();
            return $this->getResponse(true, 'Exchange rate fetched successfully', $exchangeRate);
        }, true);
    }

    public function show(Request $request)
    {
        $this->process(function () use ($request) {
            // use query params to get the exchange rate
            $fromCurrency = $request->query('from_currency');
            $toCurrency = $request->query('to_currency');
            $exchangeRate = ExchangeRate::where('from_currency', $fromCurrency)->where('to_currency', $toCurrency)->first();
            return $this->getResponse(true, 'Exchange rate fetched successfully', $exchangeRate);
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
