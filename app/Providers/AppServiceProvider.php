<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Aggressive HTTPS forcing for Railway
        $this->forceHttpsEverywhere();
    }

    private function forceHttpsEverywhere(): void
    {
        // Force HTTPS scheme
        URL::forceScheme('https');
        
        // Set server variables to indicate HTTPS
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = 443;
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $_SERVER['HTTP_X_FORWARDED_PORT'] = 443;
        
        // Force current request to be secure
        if (app()->has('request')) {
            $request = app('request');
            $request->server->set('HTTPS', 'on');
            $request->server->set('SERVER_PORT', 443);
            $request->server->set('HTTP_X_FORWARDED_PROTO', 'https');
        }
    }
}