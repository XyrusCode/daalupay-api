<?php

namespace DaluPay\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
// use DaluPay\Providers\EmployeeProvider;
// use DaluPay\Models\User;
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
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });


    // Gate::define('admin', function (User $user) {
    //     return $user->is_admin;
    //     });
    }
}
