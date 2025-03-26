<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        $usuario->save();

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
            $validator = $request->validate([
                'nombre' => 'required',
                'email' => 'required|email|unique:usuarios',
                'password' => 'required|min:6',
                'telefono' => 'required',
            ]);

            $usuario = new Usuario();

            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->password = $request->password;
            $usuario->telefono = $request->telefono;

            $usuario->save();

            return redirect()->route('usuarios.paginate')
                ->with('success', 'Usuario creado correctamente');
                
        } catch(Exception $e){
            return back()->withInput()
                ->with('error', 'Hubo un error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $query = Usuario::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
            });
        }
        
        $usuarios = $query->paginate($perPage);
        
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

    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            
            $request->validate([
                'nombre' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('usuarios')->ignore($id)
                ],
                'telefono' => 'required',
            ]);

            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->telefono = $request->telefono;
            
            $usuario->save();

            return redirect()->route('usuarios.paginate')
                ->with('success', 'Usuario actualizado correctamente');
                
        } catch (ModelNotFoundException $e) {
            return redirect()->route('usuarios.paginate')
                ->with('error', 'Usuario no encontrado');
        } catch (Exception $e) {
            return redirect()->route('usuarios.paginate')
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try{
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();
            
            return redirect()->route('usuarios.paginate')
                ->with('success', 'Usuario eliminado correctamente');
                
        } catch(ModelNotFoundException $e){
            return redirect()->route('usuarios.paginate')
                ->with('error', 'Usuario no encontrado');
        } catch(Exception $e){
            return redirect()->route('usuarios.paginate')
                ->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        return $this->destroy($id);
    }

    public function search(Request $request)
    {
        $nombre = $request->nombre;
        $usuarios = Usuario::where('nombre', 'like', "%$nombre%")->get();
        return view('user.search', ['usuarios' => $usuarios]);
    }
    
    public function verificarEmail(Request $request)
    {
        $email = $request->input('email');
        $userId = $request->input('userId');
        
        $exists = Usuario::where('email', $email)
            ->where('id', '!=', $userId)
            ->exists();
            
        return response()->json(['exists' => $exists]);
    }
}