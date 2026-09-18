<?php

namespace App\Providers;

use App\Mail\Transport\MicrosoftGraphTransport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Services\WeatherService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Paginator::useBootstrapFour(); 

        // Office 365 sending over the Graph API (SMTP AUTH is disabled on the tenant).
        Mail::extend('microsoft', function (array $config) {
            return new MicrosoftGraphTransport(
                (string) ($config['tenant_id'] ?? ''),
                (string) ($config['client_id'] ?? ''),
                (string) ($config['client_secret'] ?? ''),
                (string) ($config['from'] ?? config('mail.from.address')),
                (int) ($config['timeout'] ?? 30),
            );
        });

        Schema::defaultStringLength(191);

        Blade::if('canDo', function ($permission) {
            $user = request()->user();
            return $user && $user->hasPermission($permission);
        });

        // Share weather data with fixed nav component only
        View::composer('user.components.fixed-nav', function ($view) {
            try {
                $weather = app(WeatherService::class)->current();
            } catch (\Throwable $e) {
                $weather = null;
            }
            $view->with('weather', $weather);
        });
    }
}
