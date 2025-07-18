<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }
        
        if (!Auth::user()->esAdmin()) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}