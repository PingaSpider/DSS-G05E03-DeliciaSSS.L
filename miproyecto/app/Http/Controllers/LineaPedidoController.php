<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineaPedido;
use App\Models\Pedido;
use App\Models\Producto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LineaPedidoController extends Controller
{
    public function create()
    {
        $pedidos = Pedido::all();
        $productos = Producto::all();
        return view('lineaPedido.create', compact('pedidos', 'productos'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'linea' => 'required|string|unique:linea_pedidos',
                'cantidad' => 'required|integer|min:1',
                'precio' => 'required|numeric|min:0',
                'pedido_id' => 'required|exists:pedidos,cod',
                'producto_id' => 'required|exists:productos,cod',
            ]);

            DB::beginTransaction();

            // Crear la línea de pedido
            $lineaPedido = new LineaPedido();
            $lineaPedido->linea = $request->linea;
            $lineaPedido->cantidad = $request->cantidad;
            $lineaPedido->precio = $request->precio;
            $lineaPedido->pedido_id = $request->pedido_id;
            $lineaPedido->producto_id = $request->producto_id;
            $lineaPedido->save();

            DB::commit();
            return redirect()->route('lineaPedidos.paginate')
                ->with('success', 'Línea de pedido creada exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear la línea de pedido: ' . $e->getMessage());
        }
    }

    public function edit($linea)
    {
        try {
            $lineaPedido = LineaPedido::findOrFail($linea);
            $pedidos = Pedido::all();
            $productos = Producto::all();
            return view('lineaPedido.edit', compact('lineaPedido', 'pedidos', 'productos'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('lineaPedidos.paginate')
                ->with('error', 'Línea de pedido no encontrada');
        }
    }

    public function update(Request $request, $linea)
    {
        try {
            $request->validate([
                'cantidad' => 'required|integer|min:1',
                'precio' => 'required|numeric|min:0',
                'pedido_id' => 'required|exists:pedidos,cod',
                'producto_id' => 'required|exists:productos,cod',
            ]);

            DB::beginTransaction();

            $lineaPedido = LineaPedido::findOrFail($linea);
            $lineaPedido->cantidad = $request->cantidad;
            $lineaPedido->precio = $request->precio;
            $lineaPedido->pedido_id = $request->pedido_id;
            $lineaPedido->producto_id = $request->producto_id;
            $lineaPedido->save();

            DB::commit();
            return redirect()->route('lineaPedidos.paginate')
                ->with('success', 'Línea de pedido actualizada exitosamente');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('lineaPedidos.paginate')
                ->with('error', 'Línea de pedido no encontrada');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la línea de pedido: ' . $e->getMessage());
        }
    }

    public function destroy($linea)
    {
        try {
            DB::beginTransaction();

            $lineaPedido = LineaPedido::findOrFail($linea);
            $lineaPedido->delete();

            DB::commit();
            return redirect()->route('lineaPedidos.paginate')
                ->with('success', 'Línea de pedido eliminada exitosamente');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('lineaPedidos.paginate')
                ->with('error', 'Línea de pedido no encontrada');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('lineaPedidos.paginate')
                ->with('error', 'Error al eliminar la línea de pedido: ' . $e->getMessage());
        }
    }

    public function show($linea)
    {
        try {
            $lineaPedido = LineaPedido::with('pedido', 'producto')->findOrFail($linea);
            return view('lineaPedido.show', compact('lineaPedido'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('lineaPedidos.paginate')
                ->with('error', 'Línea de pedido no encontrada');
        }
    }

    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $query = LineaPedido::with(['pedido', 'producto']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('linea', 'like', "%{$search}%")
                  ->orWhere('cantidad', 'like', "%{$search}%")
                  ->orWhere('precio', 'like', "%{$search}%")
                  ->orWhereHas('pedido', function($query) use ($search) {
                      $query->where('cod', 'like', "%{$search}%");
                  })
                  ->orWhereHas('producto', function($query) use ($search) {
                      $query->where('nombre', 'like', "%{$search}%");
                  });
            });
        }
        
        $lineaPedidos = $query->paginate($perPage);
        
        return view('lineaPedido.paginate', ['lineaPedidos' => $lineaPedidos]);
    }
    
    public function verificarCodigo(Request $request)
    {
        $linea = $request->input('linea');
        $exists = LineaPedido::where('linea', $linea)->exists();
            
        return response()->json(['exists' => $exists]);
    }

    // Método adicional para crear líneas directamente desde un pedido
    public function createForPedido($pedido_id)
    {
        try {
            $pedido = Pedido::findOrFail($pedido_id);
            $productos = Producto::all();
            return view('lineaPedido.createForPedido', compact('pedido', 'productos'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pedidos.paginate')
                ->with('error', 'Pedido no encontrado');
        }
    }
}