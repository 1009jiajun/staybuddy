<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force Laravel to always use your tunnel domain
        URL::forceRootUrl(config('app.url'));
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
