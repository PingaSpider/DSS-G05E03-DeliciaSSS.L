<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Pedido;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado
     */
    public function profile(Request $request)
    {
        try {
            // En una aplicación real, obtendrías el usuario autenticado
            // Para simular, usamos la sesión o el primer usuario
            $usuarioId = session('user_id') ?? Usuario::first()->id;
            $usuario = Usuario::findOrFail($usuarioId);
            
            // Obtener la pestaña activa si la hay
            $activeTab = $request->query('tab', 'cuenta');
            
            // Si la pestaña activa es 'pedidos', obtenemos la lista de pedidos con filtros
            $pedidos = collect();
            
            if ($activeTab === 'pedidos') {
                $query = Pedido::where('usuario_id', $usuario->id)
                    ->whereNotIn('estado', ['en_cesta']);
                
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
            
            return view('user', [
                'user' => $usuario,
                'pedidos' => $pedidos,
                'activeTab' => $activeTab
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
            // En producción: $usuario = Auth::user();
            $usuarioId = $request->user_id ?? session('user_id');
            $usuario = Usuario::findOrFail($usuarioId);
            
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
     * Muestra un pedido específico
     */
    public function showOrder($orderId)
    {
        try {
            // En producción: $usuario_id = Auth::id();
            $usuarioId = session('user_id') ?? Usuario::first()->id;
            
            // Verificar que el pedido pertenezca al usuario
            $pedido = Pedido::where('cod', $orderId)
                ->where('usuario_id', $usuarioId)
                ->firstOrFail();
            
            // Cargar las líneas del pedido con sus productos
            $pedido->load('lineasPedido.producto');
            
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
            // En producción: $usuario_id = Auth::id();
            $usuarioId = $request->user_id ?? session('user_id') ?? Usuario::first()->id;
            
            // Iniciar transacción
            DB::beginTransaction();
            
            // Obtener el pedido original
            $pedidoOriginal = Pedido::where('cod', $orderId)
                ->where('usuario_id', $usuarioId)
                ->with('lineasPedido')
                ->firstOrFail();
                
            // Crear un nuevo pedido
            $nuevoCod = 'P' . time() . rand(100, 999);
            $nuevoPedido = new Pedido();
            $nuevoPedido->cod = $nuevoCod;
            $nuevoPedido->fecha = now();
            $nuevoPedido->estado = 'Pendiente';
            $nuevoPedido->usuario_id = $usuarioId;
            $nuevoPedido->save();
            
            // Copiar las líneas del pedido original
            foreach ($pedidoOriginal->lineasPedido as $lineaOriginal) {
                $nuevaLinea = new \App\Models\LineaPedido();
                $nuevaLinea->linea = 'L' . time() . rand(100, 999);
                $nuevaLinea->cantidad = $lineaOriginal->cantidad;
                $nuevaLinea->precio = $lineaOriginal->precio;
                $nuevaLinea->estado = 'Pendiente';
                $nuevaLinea->pedido_id = $nuevoCod;
                $nuevaLinea->producto_id = $lineaOriginal->producto_id;
                $nuevaLinea->save();
            }
            
            // Confirmar transacción
            DB::commit();
            
            return redirect()->route('user.profile', ['tab' => 'pedidos'])
                ->with('success', 'Pedido repetido correctamente');
                
        } catch (ModelNotFoundException $e) {
            // Rollback en caso de error
            DB::rollBack();
            
            return redirect()->route('user.profile', ['tab' => 'pedidos'])
                ->with('error', 'Pedido no encontrado');
        } catch (Exception $e) {
            // Rollback en caso de error
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
            // En producción: $usuario_id = Auth::id();
            $usuarioId = session('user_id') ?? $request->user_id;
            
            // Verificar que el pedido pertenezca al usuario y esté en estado pendiente
            $pedido = Pedido::where('cod', $orderId)
                ->where('usuario_id', $usuarioId)
                ->where('estado', 'Pendiente')
                ->firstOrFail();
            
            // Cambiar estado a cancelado
            $pedido->estado = 'Cancelado';
            $pedido->save();
            
            // Actualizar el estado de las líneas de pedido
            \App\Models\LineaPedido::where('pedido_id', $orderId)
                ->update(['estado' => 'Cancelado']);
            
            return redirect()->route('user.profile', ['tab' => 'pedidos'])
                ->with('success', 'Pedido cancelado correctamente');
                
        } catch (ModelNotFoundException $e) {
            return redirect()->route('user.profile', ['tab' => 'pedidos'])
                ->with('error', 'Pedido no encontrado o no puede ser cancelado');
        } catch (Exception $e) {
            return back()->with('error', 'Error al cancelar el pedido: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra la página de registro
     */
    public function register()
    {
        return view('registro');
    }
    
    /**
     * Procesa el registro de un nuevo usuario
     */
    public function storeUser(Request $request)
    {
        try {
            $validator = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'nullable|string|max:255',
                'email' => 'required|email|unique:usuarios',
                'password' => 'required|min:6|confirmed',
                'telefono' => 'required|string|max:20',
            ]);
            
            $usuario = new Usuario();
            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
            $usuario->telefono = $request->telefono;
            $usuario->save();
            
            // En una aplicación real, aquí harías login automático
            // Auth::login($usuario);
            
            // Simular login guardando en sesión
            session(['user_id' => $usuario->id]);
            
            return redirect()->route('user.profile')
                ->with('success', 'Cuenta creada correctamente');
                
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear la cuenta: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra la página de login
     */
    public function login()
    {
        return view('login');
    }
    
    /**
     * Procesa el login de un usuario
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // En una aplicación real, usarías Auth:
        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();
        //     return redirect()->intended(route('user.profile'));
        // }
        
        // Simulación para desarrollo:
        $usuario = Usuario::where('email', $request->email)->first();
        
        if ($usuario && Hash::check($request->password, $usuario->password)) {
            // Simular una sesión guardando en la sesión
            session(['user_id' => $usuario->id]);
            return redirect()->route('user.profile');
        }
        
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas son incorrectas.',
        ])->withInput();
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public function logout(Request $request)
    {
        // En una aplicación real:
        Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        
        // Simulación para desarrollo:
        session()->forget('user_id');
        
        return redirect()->route('home');
    }
    
    /**
     * Verifica si un email ya está registrado
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = Usuario::where('email', $email)->exists();
        
        return response()->json(['exists' => $exists]);
    }
}