<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Support\Facades\Route;

class MiscController extends BaseController
{
    public function getAppInfo()
    {
        return response()->json([
            'app' => config('app.name'),
            'version' => config('app.version'),
        ]);
    }

    public function getApiDocs()
    {
        // Get all routes
        $routes = collect(Route::getRoutes())
        ->filter(function ($route) {
            // Only include routes from the 'api' middleware group
            return in_array('web', $route->middleware());
        })
        ->map(function ($route) {
            return [
                'method' => $route->methods()[0],
                'uri' => $route->uri(),
                'action' => $route->getActionName(),
                'middleware' => $route->middleware(),
            ];
        });

         // Group routes by a category (this could be based on URI or route name, etc.)
        $groupedRoutes = $routes->groupBy(function ($route) {
            if (strpos($route['uri'], 'transactions') !== false) {
                return 'Transactions';
            } elseif (strpos($route['uri'], 'db') !== false) {
                return 'Database';
            } elseif (strpos($route['uri'], 'users') !== false) {
                return 'Users';
            } else {
                return 'Others';
            }
        });

        return view('docs', ['groupedRoutes' => $groupedRoutes]);
    }
}
