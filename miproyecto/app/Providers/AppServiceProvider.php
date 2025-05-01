<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\DatabaseStore;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar manualmente los servicios de caché
        $this->app->singleton('cache.store', function ($app) {
            return Cache::driver();
        });

        // Asegurarnos de que el driver de database esté correctamente configurado
        $this->app->extend('cache.stores.database', function ($store, $app) {
            return $store;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}