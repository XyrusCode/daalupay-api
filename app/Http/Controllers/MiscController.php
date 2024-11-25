<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller {


    public function getAppInfo() {
        return response()->json([
            'app' => config('app.name'),
            'version' => config('app.version'),
        ]);
    }
}
