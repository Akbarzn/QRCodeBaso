<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

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
        //
         $cfg = config('midtrans');

    Config::$serverKey = $cfg['server_key'];
    Config::$isProduction = $cfg['production'];
    Config::$isSanitized = $cfg['sanitized'];
    Config::$is3ds = $cfg['3ds'];
    }
}
