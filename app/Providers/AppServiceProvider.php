<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
       if (config('app.env') === 'production') {
        URL::forceScheme('https');
        Livewire::setAssetUrl(config('app.url'));
        }
    }
}
