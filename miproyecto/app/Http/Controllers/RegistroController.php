<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class RegistroController extends Controller
{
    /**
     * Mostrar el formulario de registro
     */
    public function mostrar()
    {
        return view('registro');
    }

    /**
     * Procesar el registro de un nuevo usuario
     */
    public function registrar(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'telefono' => 'nullable|string|max:15',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Crear el usuario
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
        ]);

        // Iniciar sesión con el usuario recién creado
        Auth::login($usuario);

        // Redireccionar a la página principal usando RouteServiceProvider::HOME
        return redirect(RouteServiceProvider::HOME)->with('status', 'Cuenta creada exitosamente');
    
    }
}