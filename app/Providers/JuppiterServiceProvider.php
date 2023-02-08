<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\JuppiterService;

class JuppiterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('JuppiterService',JuppiterService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
