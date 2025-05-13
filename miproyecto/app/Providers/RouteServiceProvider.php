<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * La ruta a la "página principal" de la aplicación.
     * Esta es la ruta donde se redirigirá a los usuarios después del inicio de sesión.
     *
     * @var string
     */
    public const HOME = '/mi-perfil';

    /**
     * Define tus rutas del router model binding, patrones, etc.
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            
            if (file_exists(base_path('routes/api.php'))) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group(base_path('routes/api.php'));
            }
        });
        // Registrar el middleware aquí
        Route::aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
    }

    
    /**
     * Configura los limitadores de velocidad para la aplicación.
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

