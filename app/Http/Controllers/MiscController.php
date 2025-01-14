<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
class MiscController extends BaseController
{

    public function getAppInfo()
    {
        return response()->json([
            'app' => config('app.name'),
            'version' => config('app.version'),
        ]);
    }

    public function getAppDocs()
    {
        return view('docs');
    }

    //
    public function runArtisanCommand($command)
    {

        try {

            Artisan::call($command. ' --force');

            $output = Artisan::output();

            return response()->json(['success' => true, 'message' => $output]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }


        }

}
