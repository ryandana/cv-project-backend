<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (App::environment('production')) {
        // Trust the proxy headers that Railway sets
        request()->setTrustedProxies(
            ['*'],
            \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL
        );

        // Force all generated URLs to use https
        URL::forceScheme('https');
        }
    }
}
