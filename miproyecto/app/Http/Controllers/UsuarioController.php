<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UsuarioController extends Controller
{
    public function create_get()
    {
        return view('user.create');
    }

    public function create_post(Request $request){

        $nombre = $request->nombre;
        $email = $request->email;
        $password = $request->password;
        $telefono = $request->telefono;

        $usuario = new Usuario();
        $usuario->nombre = $nombre;
        $usuario->email = $email;
        $usuario->password = $password;
        $usuario->telefono = $telefono;
        $usuario->save();  // Changed from $user->save() to $usuario->save()

        return view('user.profile',['usuario'=>$usuario]);
    }

    public function show_get($id)
    {   
        try{
            $usuario = Usuario::findOrFail($id);
            return view('user.profile', ['usuario' => $usuario]);
        }catch(ModelNotFoundException $e){
            return response()->json(["message" => "user id = $id not found"], 404);
        }
    }

    public function show_post(Request $request)
    {
        $id = $request->id;
        try{
            $usuario = Usuario::findOrFail($id);
            return view('user.profile', ['usuario' => $usuario]);
        }catch(ModelNotFoundException $e){
            return response()->json(["message" => "user id = $id not found"], 404);
        }
    }

    public function store(Request $request)
    {
        
        try{
            $request->validate([
                'nombre' => 'required',
                'email' => 'required',
                'password' => 'required',
                'telefono' => 'nullable',
            ]);

            $usuario = new Usuario();

            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->password = $request->password;
            $usuario->telefono = $request->telefono;

            $usuario->save();

            return view('user.show', ['usuario' => $usuario]);
        }catch(Exception $e){
            return response()->json(["message" => $e->getMessage()], 500);
        }

    }

    public function paginate(Request $request)
    {
        $usuarios = Usuario::paginate($request->per_page);
        return view('user.paginate', ['usuarios' => $usuarios]);
    }

    public function edit(Request $request, $id)
    {
        try{
            $usuario = Usuario::findOrFail($id);
            return view('user.edit', ['usuario' => $usuario]);
        }catch(ModelNotFoundException $e){
            return response()->json(["message" => "user id = $id not found"], 404);
        }
    }

    public function delete($id)
    {
        try{
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();
            return response()->json(["message" => "user id = $id deleted"], 200);
        }catch(ModelNotFoundException $e){
            return response()->json(["message" => "user id = $id not found"], 404);
        }
    }

    public function search(Request $request)
    {
        $nombre = $request->nombre;
        $usuarios = Usuario::where('nombre', 'like', "%$nombre%")->get();
        return view('user.search', ['usuarios' => $usuarios]);
    }
}