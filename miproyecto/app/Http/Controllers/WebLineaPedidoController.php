<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\LineaPedido;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class WebLineaPedidoController extends Controller
{
    /**
     * Añadir un producto al carrito
     */
    public function addToCart(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'producto_id' => 'required|exists:productos,cod',
                'cantidad' => 'integer|min:1|max:10'
            ]);
            
            $cantidad = $request->cantidad ?? 1;
            $producto = Producto::findOrFail($request->producto_id);
            
            // Verificar stock
            if ($producto->stock < $cantidad) {
                throw new Exception('No hay suficiente stock disponible');
            }
            
            $usuario = Usuario::where('email', Auth::user()->email)->firstOrFail();
            
            // Obtener o crear el carrito actual
            $carrito = Pedido::carritoActual($usuario->id)->first();
            
            if (!$carrito) {
                $carrito = new Pedido();
                $carrito->cod = Pedido::generarSiguienteCodigo();
                $carrito->fecha = now();
                $carrito->estado = Pedido::ESTADO_EN_CARRITO;
                $carrito->usuario_id = $usuario->id;
                $carrito->save();
            }
            
            // Añadir el producto al carrito
            $carrito->agregarProducto($producto, $cantidad);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Producto añadido al carrito',
                'cartItemsCount' => $carrito->lineasPedido->count(),
                'total' => $carrito->total
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Actualizar cantidad de un item del carrito
     */
    public function updateQuantity(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'linea_id' => 'required|exists:linea_pedidos,linea',
                'cantidad' => 'required|integer|min:1|max:10'
            ]);
            
            $lineaPedido = LineaPedido::findOrFail($request->linea_id);
            
            // Verificar que la línea pertenece al carrito del usuario
            $usuario = Usuario::where('email', Auth::user()->email)->firstOrFail();
            if ($lineaPedido->pedido->usuario_id !== $usuario->id || 
                !$lineaPedido->pedido->estaEnCarrito()) {
                throw new Exception('No tienes permiso para modificar este item');
            }
            
            // Verificar stock
            if ($lineaPedido->producto->stock < $request->cantidad) {
                throw new Exception('No hay suficiente stock disponible');
            }
            
            // Actualizar cantidad
            $lineaPedido->cantidad = $request->cantidad;
            $lineaPedido->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'total' => $lineaPedido->pedido->total,
                'subtotal' => $lineaPedido->subtotal
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Eliminar un item del carrito
     */
    public function removeFromCart($lineaId)
    {
        try {
            DB::beginTransaction();
            
            $lineaPedido = LineaPedido::findOrFail($lineaId);
            
            // Verificar que la línea pertenece al carrito del usuario
            $usuario = Usuario::where('email', Auth::user()->email)->firstOrFail();
            if ($lineaPedido->pedido->usuario_id !== $usuario->id || 
                !$lineaPedido->pedido->estaEnCarrito()) {
                throw new Exception('No tienes permiso para eliminar este item');
            }
            
            $carrito = $lineaPedido->pedido;
            $lineaPedido->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'total' => $carrito->total,
                'cartItemsCount' => $carrito->lineasPedido->count()
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}