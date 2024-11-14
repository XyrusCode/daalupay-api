<?php

namespace DaluPay\Http\Controllers;

class TransactionController extends Controller
{

    public function index()
    {
        return view('transactions.index');
    }
}
