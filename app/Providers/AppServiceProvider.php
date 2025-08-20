<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

public function boot()
{
    if (App::environment('production')) {
        // Trust the proxy headers that Railway sets
        request()->setTrustedProxies(
            ['*'],
            Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_PORT
        );
        
        // Force all generated URLs to use https
        URL::forceScheme('https');
        URL::forceRootUrl('https://cv-project-backend-production.up.railway.app');
        
        // Fix session configuration for Railway
        config([
            'app.url' => 'https://cv-project-backend-production.up.railway.app',
            'session.secure' => true,
            'session.same_site' => 'lax',
            'session.domain' => null,
        ]);
    }
}
}
