<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Observers\BebidaObserver;
use App\Observers\ComidaObserver;
use App\Observers\MenuObserver;
use App\Observers\ProductoObserver;
use App\Models\Bebida;
use App\Models\Comida;
use App\Models\Menu;
use App\Models\Producto;


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
        Menu::observe(MenuObserver::class);
        Producto::observe(ProductoObserver::class);
        Bebida::observe(BebidaObserver::class);
        Comida::observe(ComidaObserver::class);    //
    }


}
