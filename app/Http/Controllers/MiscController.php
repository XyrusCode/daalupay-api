<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MiscController extends BaseController
{

    public function getAppInfo()
    {
        return response()->json([
            'app' => config('app.name'),
            'version' => config('app.version'),
        ]);
    }

    // 
    public function getAppDocs()
    {
        return view('docs')->with([
            'title' => 'API Documentation'
        ]);
    }


    public function getApiDocs()
    {
        // Get all routes
        $routes = collect(Route::getRoutes())
            ->filter(function ($route) {
                // Include routes with 'web' middleware
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

        // Fetch models and their fillable attributes
        $modelPath = app_path('Models'); // Adjust if models are in a different directory
        $models = [];

        if (File::exists($modelPath)) {
            foreach (File::files($modelPath) as $file) {
                $modelName = Str::replaceLast('.php', '', $file->getFilename());
                $namespace = 'DaaluPay\\Models\\' . $modelName;

                if (class_exists($namespace)) {
                    $modelInstance = new $namespace;

                    // Check if it's an Eloquent model
                    if (is_subclass_of($modelInstance, \Illuminate\Database\Eloquent\Model::class)) {
                        $fillable = property_exists($modelInstance, 'fillable') ? $modelInstance->getFillable() : [];
                        $models[] = [
                            'name' => $modelName,
                            'fillable' => $fillable,
                        ];
                    } else {
                        $models[] = [
                            'name' => $modelName,
                            'fillable' => ['Not an Eloquent model'],
                        ];
                    }
                }
            }
        }

        // Group routes by category
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

        return view('docs', compact('groupedRoutes', 'models'));
    }
}
