<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{
    public function create()
    {
        // Obtenemos la lista de usuarios para mostrarlos en el formulario
        $usuarios = Usuario::all();
        return view('pedido.create', compact('usuarios'));
    }
    
    public function store(Request $request)
    {
        try {
            // Validamos incluyendo la existencia del usuario_id
            $request->validate([
                'cod' => 'required|string|max:5|unique:pedidos',
                'fecha' => 'required|date',
                'estado' => 'required|string',
                'usuario_id' => 'required|exists:usuarios,id',
            ]);
        
            // Usamos una transacción para asegurar la consistencia de datos
            DB::beginTransaction();
            
            // Crear el pedido
            $pedido = new Pedido();
            $pedido->cod = $request->cod;
            $pedido->fecha = $request->fecha;
            $pedido->estado = $request->estado;
            $pedido->usuario_id = $request->usuario_id;
            $pedido->save();
            
            DB::commit();
            return redirect()->route('pedidos.paginate')
                ->with('success', 'Pedido creado exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear el pedido: ' . $e->getMessage());
        }
    }
    
    public function edit($cod)
    {
        try {
            $pedido = Pedido::findOrFail($cod);
            $usuarios = Usuario::all();
            return view('pedido.edit', compact('pedido', 'usuarios'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pedidos.paginate')
                ->with('error', 'Pedido no encontrado');
        }
    }
    
    public function update(Request $request, $cod)
    {
        try {
            // Validamos incluyendo la existencia del usuario_id
            $request->validate([
                'fecha' => 'required|date',
                'estado' => 'required|string',
                'usuario_id' => 'required|exists:usuarios,id',
            ]);
        
            DB::beginTransaction();
            
            $pedido = Pedido::findOrFail($cod);
            $pedido->fecha = $request->fecha;
            $pedido->estado = $request->estado;
            $pedido->usuario_id = $request->usuario_id;
            $pedido->save();
            
            DB::commit();
            return redirect()->route('pedidos.paginate')
                ->with('success', 'Pedido actualizado exitosamente');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('pedidos.paginate')
                ->with('error', 'Pedido no encontrado');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar el pedido: ' . $e->getMessage());
        }
    }
    
    public function destroy($cod)
    {
        try {
            DB::beginTransaction();
            
            $pedido = Pedido::findOrFail($cod);
            $pedido->delete();
            
            DB::commit();
            return redirect()->route('pedidos.paginate')
                ->with('success', 'Pedido eliminado exitosamente');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('pedidos.paginate')
                ->with('error', 'Pedido no encontrado');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('pedidos.paginate')
                ->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
        }
    }
    
    public function show($cod)
    {
        try {
            $pedido = Pedido::with('usuario', 'lineaPedidos.producto')->findOrFail($cod);
            return view('pedido.show', compact('pedido'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pedidos.paginate')
                ->with('error', 'Pedido no encontrado');
        }
    }
    
    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $query = Pedido::with('usuario');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('cod', 'like', "%{$search}%")
                  ->orWhere('estado', 'like', "%{$search}%")
                  ->orWhere('fecha', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function($query) use ($search) {
                      $query->where('nombre', 'like', "%{$search}%");
                  });
            });
        }
        
        $pedidos = $query->paginate($perPage);
        
        return view('pedido.paginate', ['pedidos' => $pedidos]);
    }
    
    public function verificarCodigo(Request $request)
    {
        $codigo = $request->input('cod');
        $exists = Pedido::where('cod', $codigo)->exists();
            
        return response()->json(['exists' => $exists]);
    }

    // Mantener este método para compatibilidad
    public function index()
    {
        return $this->paginate(request());
    }
}