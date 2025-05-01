<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /**
     * Mostrar la vista de login
     */
    public function mostrar()
    {
        return view('login');
    }

    /**
     * Manejar la solicitud de autenticación
     */
    public function autenticar(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redireccionar a la pagina de usuario mi-perfil
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Retornar con error si la autenticación falla
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión
     */
    public function cerrarSesion(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}