<?php

namespace DaaluPay\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

// use DaaluPay\Providers\EmployeeProvider;
// use DaaluPay\Models\User;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
           // Add new user provider with employee model
        Auth::provider('employees', function ($app, array $config) {
            return new EmployeeProvider($app['hash'], $config['model']);
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

         RateLimiter::for('global', function (Request $request) {
        return Limit::perMinute(1000);
    });
    
            RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });


    // Gate::define('admin', function (User $user) {
    //     return $user->is_admin;
    //     });
    }
}
