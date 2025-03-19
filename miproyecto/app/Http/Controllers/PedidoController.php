<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

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
        // Validamos incluyendo la existencia del usuario_id
        $request->validate([
            'cod' => 'required|string|max:5|unique:pedidos',
            'fecha' => 'required|date',
            'estado' => 'required|string',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);
    
        // Usamos una transacción para asegurar la consistencia de datos
        try {
            DB::beginTransaction();
            
            // Crear el pedido
            $pedido = new Pedido();
            $pedido->cod = $request->cod;
            $pedido->fecha = $request->fecha;
            $pedido->estado = $request->estado;
            $pedido->usuario_id = $request->usuario_id;
            $pedido->save();
            
            DB::commit();
            return redirect()->route('pedidos.index')->with('success', 'Pedido creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear el pedido: ' . $e->getMessage()])->withInput();
        }
    }
    
    // Función de modificar
    public function edit($cod)
    {
        $pedido = Pedido::findOrFail($cod);
        $usuarios = Usuario::all();
        return view('pedido.edit', compact('pedido', 'usuarios'));
    }
    
    // Función de actualizar
    public function update(Request $request, $cod)
    {
        // Validamos incluyendo la existencia del usuario_id
        $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|string',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);
    
        try {
            DB::beginTransaction();
            
            $pedido = Pedido::findOrFail($cod);
            $pedido->fecha = $request->fecha;
            $pedido->estado = $request->estado;
            $pedido->usuario_id = $request->usuario_id;
            $pedido->save();
            
            DB::commit();
            return redirect()->route('pedidos.index')->with('success', 'Pedido actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar el pedido: ' . $e->getMessage()])->withInput();
        }
    }
    
    // Función de eliminar
    public function destroy($cod)
    {
        try {
            DB::beginTransaction();
            
            $pedido = Pedido::findOrFail($cod);
            $pedido->delete();
            
            DB::commit();
            return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el pedido: ' . $e->getMessage()]);
        }
    }
    
    // Agregar también el método index para listar todos los pedidos
    public function index()
    {
        $pedidos = Pedido::with('usuario')->get();
        return view('pedido.index', compact('pedidos'));
    }
    
    // Agregar el método show para ver un pedido específico
    public function show($cod)
    {
        $pedido = Pedido::with('usuario', 'lineaPedidos.producto')->findOrFail($cod);
        return view('pedido.show', compact('pedido'));
    }
}