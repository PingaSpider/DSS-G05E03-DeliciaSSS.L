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
        
        // Obtener el último código de línea
        $ultimaLinea = LineaPedido::orderBy('linea', 'desc')->first();
        
        if ($ultimaLinea) {
            // Extraer el número de la línea (formato Lxxxx)
            $numeroActual = intval(substr($ultimaLinea->linea, 1));
            // Incrementa el número y formatea con 4 dígitos
            $siguienteLinea = 'L' . str_pad($numeroActual + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Si no hay líneas anteriores, comenzar con L0001
            $siguienteLinea = 'L0001';
        }
        
        return view('lineaPedido.create', compact('pedidos', 'productos', 'siguienteLinea'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'linea' => 'required|string|unique:linea_pedidos',
                'cantidad' => 'required|integer|min:1',
                'pedido_id' => 'required|exists:pedidos,cod',
                'producto_id' => 'required|exists:productos,cod',
            ]);

            DB::beginTransaction();

            // Crear la línea de pedido
            $producto = Producto::findOrFail($request->producto_id);
            $pedido = Pedido::findOrFail($request->pedido_id);
            $lineaPedido = new LineaPedido();
            $lineaPedido->linea = $request->linea;
            $lineaPedido->cantidad = $request->cantidad;
            $lineaPedido->precio = $producto->pvp;
            $lineaPedido->pedido_id = $request->pedido_id;
            $lineaPedido->producto_id = $request->producto_id;
            $lineaPedido->estado = $pedido->estado;
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
                'pedido_id' => 'required|exists:pedidos,cod',
                'producto_id' => 'required|exists:productos,cod',
            ]);

            DB::beginTransaction();

            $lineaPedido = LineaPedido::findOrFail($linea);
            $lineaPedido->cantidad = $request->cantidad;
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
        // Iniciar consulta
        $query = LineaPedido::with(['pedido', 'producto']);
        
        // Filtrar por búsqueda si existe
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('linea', 'like', "%{$search}%")
                  ->orWhere('pedido_id', 'like', "%{$search}%")
                  ->orWhereHas('producto', function($query) use ($search) {
                      $query->where('nombre', 'like', "%{$search}%");
                  });
            });
        }
        
        // Obtener parámetros de ordenación
        $sortBy = $request->get('sort_by', 'linea');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Lista de campos permitidos para ordenar
        $allowedSortFields = ['linea', 'pedido_id', 'producto_id', 'cantidad', 'precio'];
        
        // Verificar que el campo de ordenación sea válido
        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'producto_id') {
                // Ordenar por nombre de producto requiere un join
                $query->join('productos', 'linea_pedidos.producto_id', '=', 'productos.cod')
                      ->orderBy('productos.nombre', $sortOrder)
                      ->select('linea_pedidos.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            // Ordenación por defecto
            $query->orderBy('linea', 'asc');
        }
        
        // Paginar resultados
        $lineaPedidos = $query->paginate(10);
        
        // Mostrar vista con resultados
        return view('lineaPedido.paginate', compact('lineaPedidos'));
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


//Entrega2