<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Models\LineaPedido;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado
     */
    public function profile(Request $request)
    {
        try {
            // USAR AUTH EN LUGAR DE SESSION
            $usuario = Auth::user();
            
            if (!$usuario) {
                return redirect()->route('login.form')->with('error', 'Debes iniciar sesión');
            }
            
            // Obtener la pestaña activa si la hay
            $activeTab = $request->query('tab', 'cuenta');
            
            // Si la pestaña activa es 'pedidos', obtenemos la lista de pedidos con filtros
            $pedidos = collect();
            
            if ($activeTab === 'pedidos') {
                $query = Pedido::where('usuario_id', $usuario->id)
                    ->whereNotIn('estado', ['en_cesta', 'en_carrito']);
                
                // Filtrar por fecha desde
                if ($request->filled('fecha_desde')) {
                    $fechaDesde = Carbon::parse($request->fecha_desde)->startOfDay();
                    $query->where('fecha', '>=', $fechaDesde);
                }
                
                // Filtrar por fecha hasta
                if ($request->filled('fecha_hasta')) {
                    $fechaHasta = Carbon::parse($request->fecha_hasta)->endOfDay();
                    $query->where('fecha', '<=', $fechaHasta);
                }
                
                // Filtrar por número de pedido
                if ($request->filled('num_pedido')) {
                    $query->where('cod', 'like', '%' . $request->num_pedido . '%');
                }
                
                // Filtrar por estado
                if ($request->filled('estado')) {
                    $query->where('estado', $request->estado);
                }
                
                // Ordenar por fecha descendente
                $pedidos = $query->with('lineasPedido.producto')
                    ->orderBy('fecha', 'desc')
                    ->paginate(5);
            }
            
            // Datos del footer
            $direccion = (object)[
                'calle' => 'Calle Los Dolores, 44',
                'ciudad' => '03110 Alicante'
            ];
            
            $horarios = (object)[
                'semana' => 'Lun-Vie: 12:30 - 17:00',
                'finde' => 'Sáb-Dom: 12:30 - 24:00'
            ];
            
            $contacto = (object)[
                'telefono' => '678-45-20-16',
                'email' => 'delicias@gmail.com'
            ];
            
            return view('user', [
                'user' => $usuario,
                'pedidos' => $pedidos,
                'activeTab' => $activeTab,
                'direccion' => $direccion,
                'horarios' => $horarios,
                'contacto' => $contacto
            ]);
        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar el perfil: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la información personal del usuario
     */
    public function updateProfile(Request $request)
    {
        try {
            // USAR AUTH
            $usuario = Auth::user();
            
            if (!$usuario) {
                return redirect()->route('login.form')->with('error', 'Debes iniciar sesión');
            }
            
            $validator = $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('usuarios')->ignore($usuario->id)
                ],
                'telefono' => 'required|string|max:20',
            ]);
            
            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->telefono = $request->telefono;
            
            if ($request->filled('apellido')) {
                $usuario->apellido = $request->apellido;
            }
            
            $usuario->save();
            
            return redirect()->route('user.profile', ['tab' => 'cuenta'])
                ->with('success', 'Información actualizada correctamente');
                
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Usuario no encontrado');
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar perfil: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la página de pedidos
     */
    public function orders()
    {
        $usuario = Auth::user();
        
        if (!$usuario) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión');
        }
        
        $pedidos = Pedido::where('usuario_id', $usuario->id)
            ->whereNotIn('estado', ['en_carrito'])
            ->with('lineasPedido.producto')
            ->orderBy('fecha', 'desc')
            ->get();
        
        return view('user.orders', compact('usuario', 'pedidos'));
    }

    /**
     * Muestra un pedido específico
     */
    public function showOrder($orderId)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return redirect()->route('login.form')->with('error', 'Debes iniciar sesión');
            }
            
            // Verificar que el pedido pertenezca al usuario
            $pedido = Pedido::where('cod', $orderId)
                ->where('usuario_id', $usuario->id)
                ->with('lineasPedido.producto')
                ->firstOrFail();
            
            return view('order-details', [
                'pedido' => $pedido
            ]);
            
        } catch (ModelNotFoundException $e) {
            return redirect()->route('user.profile', ['tab' => 'pedidos'])
                ->with('error', 'Pedido no encontrado');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Repite un pedido anterior
     */
    public function repeatOrder(Request $request, $orderId)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return redirect()->route('login.form')->with('error', 'Debes iniciar sesión');
            }
            
            // Iniciar transacción
            DB::beginTransaction();
            
            // Obtener el pedido original
            $pedidoOriginal = Pedido::where('cod', $orderId)
                ->where('usuario_id', $usuario->id)
                ->with('lineasPedido.producto')
                ->firstOrFail();
                
            // Crear un nuevo pedido en estado carrito
            $nuevoPedido = new Pedido();
            $nuevoPedido->cod = Pedido::generarSiguienteCodigo();
            $nuevoPedido->fecha = now();
            $nuevoPedido->estado = Pedido::ESTADO_EN_CARRITO;
            $nuevoPedido->usuario_id = $usuario->id;
            $nuevoPedido->save();
            
            // Copiar las líneas del pedido original
            foreach ($pedidoOriginal->lineasPedido as $lineaOriginal) {
                if ($lineaOriginal->producto && $lineaOriginal->producto->stock > 0) {
                    $cantidad = min($lineaOriginal->cantidad, $lineaOriginal->producto->stock);
                    $nuevoPedido->agregarProducto($lineaOriginal->producto, $cantidad);
                }
            }
            
            // Confirmar transacción
            DB::commit();
            
            return redirect()->route('carrito.view')
                ->with('success', 'Pedido añadido al carrito correctamente');
                
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('user.profile', ['tab' => 'pedidos'])
                ->with('error', 'Pedido no encontrado');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al repetir el pedido: ' . $e->getMessage());
        }
    }
    
    /**
     * Cancela un pedido pendiente
     */
    public function cancelOrder(Request $request, $orderId)
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return redirect()->route('login.form')->with('error', 'Debes iniciar sesión');
            }
            
            // Verificar que el pedido pertenezca al usuario
            $pedido = Pedido::where('cod', $orderId)
                ->where('usuario_id', $usuario->id)
                ->firstOrFail();
            
            // Solo se pueden cancelar pedidos que no estén entregados o ya cancelados
            if (in_array($pedido->estado, ['entregado', 'cancelado'])) {
                return back()->with('error', 'No se puede cancelar este pedido');
            }
            
            // Cambiar estado a cancelado
            $pedido->estado = 'cancelado';
            $pedido->save();
            
            // Actualizar el estado de las líneas de pedido
            LineaPedido::where('pedido_id', $orderId)
                ->update(['estado' => 'cancelado']);
            
            return redirect()->route('user.profile', ['tab' => 'pedidos'])
                ->with('success', 'Pedido cancelado correctamente');
                
        } catch (ModelNotFoundException $e) {
            return redirect()->route('user.profile', ['tab' => 'pedidos'])
                ->with('error', 'Pedido no encontrado o no puede ser cancelado');
        } catch (Exception $e) {
            return back()->with('error', 'Error al cancelar el pedido: ' . $e->getMessage());
        }
    }
}