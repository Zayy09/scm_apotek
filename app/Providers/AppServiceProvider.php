<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        Carbon::setLocale('id');
        if ($this->app->environment('local')) {
            \URL::forceScheme('https');
        }

        \App\Models\Transaksi::observe(\App\Observers\TransaksiObserver::class);
    }
}
