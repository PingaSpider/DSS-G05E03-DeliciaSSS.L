<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function create()
    {
        return view('usuario.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:usuarios',
            'nombre' => 'required',
            'password' => 'required',
            'telefono' => 'nullable',
        ]);


        $usuario = new Usuario();

        $usuario->email = $request->email;
        $usuario->nombre = $request->nombre;
        $usuario->password = bcrypt($request->password);
        $usuario->telefono = $request->telefono;
        
        $usuario->save();

        return "Usuario creado exitosamente";
    }

    //Funcion de modificar
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuario.edit', compact('usuario'));
    }

    //Funcion de actualizar
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'telefono' => 'nullable',
        ]);

        $usuario = Usuario::findOrFail($id);

        $usuario->nombre = $request->nombre;
        $usuario->telefono = $request->telefono;

        $usuario->save();

        return "Usuario actualizado exitosamente";
    }

    //Funcion de eliminar
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return "Usuario eliminado exitosamente";
    }

}